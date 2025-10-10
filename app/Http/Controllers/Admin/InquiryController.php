<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Inquiry::with(['product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('inquiry_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('product')) {
            $query->where('product_id', $request->product);
        }

        $inquiries = $query->orderBy('created_at', 'desc')->paginate(15);
        $products = Product::active()->orderBy('name')->get();

        $statusCounts = [
            'all' => Inquiry::count(),
            'pending' => Inquiry::where('status', Inquiry::STATUS_PENDING)->count(),
            'processing' => Inquiry::where('status', Inquiry::STATUS_PROCESSING)->count(),
            'quoted' => Inquiry::where('status', Inquiry::STATUS_QUOTED)->count(),
            'converted' => Inquiry::where('status', Inquiry::STATUS_CONVERTED)->count(),
            'closed' => Inquiry::where('status', Inquiry::STATUS_CLOSED)->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'products', 'statusCounts'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->load(['product']);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,quoted,converted,closed',
            'notes' => 'nullable|string|max:2000'
        ]);

        $inquiry->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Inquiry status updated successfully.');
    }

    public function addQuote(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'quoted_price' => 'required|numeric|min:0',
            'quoted_currency' => 'required|string|max:3',
            'notes' => 'nullable|string|max:2000'
        ]);

        $inquiry->update([
            'quoted_price' => $request->quoted_price,
            'quoted_currency' => $request->quoted_currency,
            'quoted_at' => now(),
            'status' => Inquiry::STATUS_QUOTED,
            'notes' => $request->notes
        ]);

        // Here you can add logic to send email to customer with quote

        return back()->with('success', 'Quote added successfully.');
    }

    public function setFollowUp(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'follow_up_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:2000'
        ]);

        $inquiry->update([
            'follow_up_date' => $request->follow_up_date,
            'notes' => $request->notes
        ]);

        return back()->with('success', 'Follow-up date set successfully.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }

    public function export(Request $request)
    {
        // Here you can add logic to export inquiries to Excel/CSV
        // For now, we'll just return a success message
        
        return back()->with('success', 'Export functionality will be implemented soon.');
    }
}