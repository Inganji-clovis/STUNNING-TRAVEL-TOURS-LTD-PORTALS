"use client";

import { useState } from "react";
import { toast } from "sonner";
import { Plus, X, Upload, ShieldCheck, FileText } from "lucide-react";
import { createEbmRecord } from "@/lib/ebm-actions";

interface EbmFormProps {
  invoices: { id: number; document_number: string }[];
}

export default function EbmForm({ invoices }: EbmFormProps) {
  const [isOpen, setIsOpen] = useState(false);
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await createEbmRecord(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      setIsOpen(false);
    } else {
      toast.error(result.message);
    }
  }

  return (
    <>
      <button 
        onClick={() => setIsOpen(true)}
        className="flex items-center gap-3 px-8 py-4 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
      >
        <Plus size={16} /> Archive New EBM
      </button>

      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
          <div className="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <div>
                <h2 className="text-2xl font-black text-slate-900 uppercase tracking-tight italic">New EBM Record</h2>
                <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Full Integrity Archive Pass</p>
              </div>
              <button onClick={() => setIsOpen(false)} className="p-2 hover:bg-slate-200 rounded-full transition-colors">
                <X size={20} className="text-slate-400" />
              </button>
            </div>

            <form action={handleSubmit} className="p-8 space-y-6 overflow-y-auto">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Company Name</label>
                  <input name="company_name" required className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900" placeholder="e.g. RWANDA AIR" />
                </div>
                <div>
                  <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">TIN Number</label>
                  <input name="tin_number" required className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900" placeholder="e.g. 100234..." />
                </div>
                <div>
                  <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Receipt Date</label>
                  <input type="date" name="date" required className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900" />
                </div>
                <div>
                  <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Link to Invoice (Optional)</label>
                  <select name="billing_document_id" className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900">
                    <option value="">No link</option>
                    {invoices.map(inv => (
                      <option key={inv.id} value={inv.id}>{inv.document_number}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Amount (RWF)</label>
                  <input type="number" name="amount_rwf" required className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900" placeholder="0.00" />
                </div>
                <div>
                  <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">VAT Amount (RWF)</label>
                  <input type="number" name="vat_amount" className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900" placeholder="0.00" />
                </div>
              </div>

              <div>
                <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Description / Purpose</label>
                <textarea name="description" className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-bold text-slate-900 min-h-[80px]" placeholder="Brief context..."></textarea>
              </div>

              <div>
                <label className="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">EBM Scan / PDF</label>
                <div className="relative group/file">
                  <input type="file" name="file" required className="absolute inset-0 opacity-0 cursor-pointer z-10" />
                  <div className="border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center gap-3 group-hover/file:border-blue-400 transition-all bg-slate-50/50">
                    <Upload size={24} className="text-slate-400 group-hover/file:text-blue-500 transition-colors" />
                    <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover/file:text-blue-500 transition-colors">Click or drag receipt file</p>
                  </div>
                </div>
              </div>

              <div className="pt-4 flex justify-end gap-4">
                <button type="button" onClick={() => setIsOpen(false)} className="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</button>
                <button 
                  disabled={isPending}
                  className="px-10 py-4 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50"
                >
                  {isPending ? "Archiving..." : "Save EBM Record"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
}
