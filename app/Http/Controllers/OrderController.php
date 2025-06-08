<?php

namespace App\Http\Controllers;

use App\Models\BahanCetak;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('customer');

        // Search berdasarkan nomor order atau nama pelanggan
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%$search%") // asumsi id order berupa string seperti "PRT-2023-101"
                ->orWhereHas('customer', function($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                });
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Filter layanan (services) - services disimpan sebagai array JSON
        if ($service = $request->input('service')) {
            if ($service !== 'all') {
                // Cari order yang array 'services' nya mengandung $service
                $query->whereJsonContains('services', $service);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'date-desc'); // default sort terbaru
        switch ($sort) {
            case 'deadline-asc':
                $query->orderBy('deadline', 'asc');
                break;
            case 'deadline-desc':
                $query->orderBy('deadline', 'desc');
                break;
            case 'date-asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date-desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $orders = $query->paginate(10)->appends($request->query());

        // Untuk menampilkan total order (bisa dari paginated result)
        $totalOrders = $orders->total();

        $progressTotal = Order::whereIn('status', ['Menunggu', 'Dikerjakan'])->count();
        // Kirim data ke view
        return view('order.index', compact('orders', 'sort', 'totalOrders', 'progressTotal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = BahanCetak::all();
        $customers = Customer::all();
        return view('order.create', compact('customers', 'materials'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $services = $request->input('services', []);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_files.*' => 'nullable|file|max:204840',
            'deadline' => 'required|date',
            'estimateTime' => 'required|integer',
            'status' => 'required|string',
            'priority' => 'required|string',
            'specialNotes' => 'nullable|string',
            'services' => 'required|array|min:1',
        ]);

        // Validasi tambahan: pastikan customer_id sesuai dengan customer yang ada
        $customer = Customer::find($request->customer_id);
        if (!$customer) {
            return back()->withInput()->withErrors([
                'customer_id' => 'Pelanggan yang dipilih tidak valid'
            ]);
        }
        
        // Validasi tambahan tergantung layanan yang dipilih
        $customRules = [];

        if (in_array('Ketik', $services)) {
            $customRules['docType'] = 'required|string';
            $customRules['pageCount'] = 'required|integer|min:1';
        }

        if (in_array('Desain', $services)) {
            $customRules['designType'] = 'required|string';
            $customRules['designSize'] = 'required|string';
        }

        if (in_array('Cetak', $services)) {
            $customRules['printType'] = 'required|string';
            $customRules['printQuantity'] = 'required|integer|min:1';
            $customRules['bahanCetakId'] = 'required|exists:bahan_cetak,id';
        }

        $request->validate($customRules);
        
        $order = Order::create([
            'user_id'        => Auth::id(), // Menyimpan user yang login
            'customer_id'    => $request->customer_id,
            'services'       => $request->services,
            'doc_type'       => $request->docType,
            'page_count'     => $request->pageCount,
            'design_type'    => $request->designType,
            'design_size'    => $request->designSize,
            'print_type'     => $request->printType,
            'print_quantity' => $request->printQuantity,
            'bahan_cetak_id' => $request->bahanCetakId,
            'deadline'       => $request->deadline,
            'estimate_time'  => $request->estimateTime,
            'status'   => $request->status,
            'priority'       => $request->priority,
            'special_notes'  => $request->specialNotes,
        ]);

        // File upload
        if ($request->hasFile('order_files')) {
            foreach ($request->file('order_files') as $file) {
                $path = $file->store('order_files', 'public');

                $order->files()->create([
                    'filename' => 'storage/' . $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('order.index')->with('success', 'Order berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */

    public function show($orderId)
    {
        $order = Order::with([
            'progress' => function($query) {
                $query->latest();
            },
            'progress.user',
            'user',
            'customer'
        ])->findOrFail($orderId);

        return view('order.show', compact('order'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // Pastikan services berupa array
        $services = is_array($order->services) ? $order->services : json_decode($order->services, true) ?? [];

        $order->load('files');
        // Tambahkan semua field yang diperlukan
        $orderData = [
            'services' => $services,
            'docType' => $order->doc_type ?? null, // Pastikan nama kolom sesuai database
            'pageCount' => $order->page_count ?? null,
            'designType' => $order->design_type ?? null,
            'designSize' => $order->design_size ?? null,
            'printType' => $order->print_type ?? null,
            'printQuantity' => $order->print_quantity ?? null,
            'printMaterial' => $order->print_material ?? null,
            // ... tambahkan field lain yang diperlukan
        ];

        $order = (object) array_merge((array) $order->toArray(), $orderData, ['services' => $services ?? []]);

        return view('order.edit', [
            'order' => $order,
            'customers' => Customer::all(),
            'materials' => BahanCetak::all(),
            'files' => $order->files, // <-- penting: ini dari relasi
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Menunggu,Dikerjakan,Selesai,Diambil,Batal',
            'note' => 'nullable|string|max:500',
        ]);

        // Simpan progress baru
        $order->progress()->create([
            'status' => $validated['status'],
            'note' => $validated['note'],
            'user_id' => Auth::id()
        ]);

        // Update status order utama
        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status berhasil diperbarui');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'services'        => 'required|array',
            'services.*'      => 'in:Ketik,Desain,Cetak',
            'docType'         => 'nullable|required_if:services,Ketik|string|max:255',
            'pageCount'       => 'nullable|required_if:services,Ketik|integer|min:1',
            'designType'      => 'nullable|required_if:services,Desain|string|max:255',
            'designSize'      => 'nullable|required_if:services,Desain|string|max:255',
            'printType'       => 'nullable|required_if:services,Cetak|string|max:255',
            'printQuantity'   => 'nullable|required_if:services,Cetak|integer|min:1',
            'bahanCetakId'    => 'nullable|required_if:services,Cetak|exists:bahan_cetak,id',
            'deadline'        => 'required|date',
            'estimateTime'    => 'required|integer',
            'status'          => 'required|string',
            'priority'        => 'required|string',
            'specialNotes'    => 'nullable|string',
            'order_files'     => 'nullable|array',
            'order_files.*'   => 'file|max:20480',
            'existing_files'  => 'nullable|array',
            'deleted_files'   => 'nullable|array',
        ]);

        // Ambil data order
        $order = Order::findOrFail($id);

        // Update data utama
        $order->customer_id    = $validated['customer_id'];
        $order->services       = $validated['services'];
        $order->deadline       = $validated['deadline'];
        $order->estimate_time   = $validated['estimateTime'];
        $order->status         = $validated['status'];
        $order->priority       = $validated['priority'];
        $order->special_notes  = $validated['specialNotes'];

        // Update detail layanan
        $order->doc_type        = in_array('Ketik', $validated['services']) ? $validated['docType'] : null;
        $order->page_count      = in_array('Ketik', $validated['services']) ? $validated['pageCount'] : null;
        $order->design_type     = in_array('Desain', $validated['services']) ? $validated['designType'] : null;
        $order->design_size     = in_array('Desain', $validated['services']) ? $validated['designSize'] : null;
        $order->print_type      = in_array('Cetak', $validated['services']) ? $validated['printType'] : null;
        $order->print_quantity  = in_array('Cetak', $validated['services']) ? $validated['printQuantity'] : null;
        $order->bahan_cetak_id  = in_array('Cetak', $validated['services']) ? $validated['bahanCetakId'] : null;

        // Hapus file yang dipilih
        if (!empty($validated['deleted_files'])) {
            foreach ($validated['deleted_files'] as $fileId) {
                $file = OrderFile::find($fileId);
                if ($file) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $file->filename));
                    $file->delete();
                }
            }
        }

        // Simpan order
        $order->save();

        // Upload file baru
        if ($request->hasFile('order_files')) {
            foreach ($request->file('order_files') as $file) {
                
                $path = $file->store('order_files', 'public');

                $order->files()->create([
                    'filename' => 'storage/' . $path,
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('order.index')->with('success', 'Data pesanan berhasil diperbarui.');
    }

    public function cancel(Request $request, Order $order)
    {
        $request->validate([
            'cancel_reason' => 'required|string',
            'cancel_notes' => 'nullable|string',
        ]);

        // Update status order menjadi Batal
        $order->update([
            'status' => 'Batal',
        ]);

        // Simpan ke order_progress
        $order->progress()->create([
            'status' => 'Batal',
            'note' => 'Alasan: ' . $request->cancel_reason . 
                    ($request->cancel_notes ? ' - Catatan: ' . $request->cancel_notes : ''),
        ]);

        return redirect()->back()->with('success', 'Order berhasil dibatalkan.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
