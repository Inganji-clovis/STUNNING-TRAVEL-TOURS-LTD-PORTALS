"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import { 
  ArrowLeft, 
  ShieldCheck, 
  Upload, 
  CheckCircle2, 
  DollarSign,
  Calendar,
  Save,
  Info,
  AlertCircle
} from "lucide-react";
import Link from "next/link";
import { payTax } from "@/lib/tax-actions";

export default function PayTaxPage({ params, taxRecord }: { params: any, taxRecord: any }) {
  const router = useRouter();
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await payTax(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      router.push(`/sales/tax?type=${taxRecord.type}`);
      router.refresh();
    } else {
      toast.error(result.message);
    }
  }

  return (
    <div className="max-w-4xl mx-auto space-y-8 pb-20 font-outfit uppercase font-black italic">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href={`/sales/tax?type=${taxRecord.type}`} className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Finalize Payment</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic">Phase 2: Fiscal Settlement Protocol</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2">
           <form action={handleSubmit} className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm space-y-8">
              <input type="hidden" name="id" value={taxRecord.id} />
              
              <div className="p-6 bg-slate-900 rounded-[2rem] text-white flex items-center justify-between shadow-xl">
                 <div>
                    <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 italic">Liability Reference</p>
                    <p className="text-lg font-black tracking-widest uppercase">#{taxRecord.declaration_number}</p>
                 </div>
                 <div className="text-right">
                    <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 italic">Amount Owed</p>
                    <p className="text-lg font-black text-emerald-400">RWF {Number(taxRecord.declared_amount).toLocaleString()}</p>
                 </div>
              </div>

              <div className="space-y-3">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Receipt Amount (Must Match Owed)</label>
                 <div className="relative">
                    <DollarSign className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="receipt_amount" type="number" step="0.01" required defaultValue={Number(taxRecord.declared_amount)} className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 text-xs shadow-inner" />
                 </div>
              </div>

              <div className="space-y-3">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Actual Payment Date</label>
                 <div className="relative">
                    <Calendar className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input type="date" name="payment_date" required className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 text-xs" />
                 </div>
              </div>

              <div className="space-y-3">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Payment Proof / Bank Receipt (PDF)</label>
                 <div className="relative group/file">
                    <input type="file" name="receipt_file" required className="absolute inset-0 opacity-0 cursor-pointer z-10" />
                    <div className="border-2 border-dashed border-slate-200 rounded-[2rem] p-10 flex flex-col items-center gap-4 group-hover/file:border-emerald-400 transition-all bg-slate-50/50">
                       <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 group-hover/file:text-emerald-500 shadow-sm transition-colors">
                          <Upload size={24} />
                       </div>
                       <div className="text-center">
                          <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Upload Official Receipt</p>
                          <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic uppercase">Cloud backup for fiscal auditing</p>
                       </div>
                    </div>
                 </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <CheckCircle2 size={16} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Compliance Validation Active</span>
                 </div>
                 <button 
                  disabled={isPending}
                  className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95 disabled:opacity-50"
                 >
                    {isPending ? "VERIFYING..." : <><Save size={18} /> Seal Settlement</>}
                 </button>
              </div>
           </form>
        </div>

        <div className="space-y-8">
           <div className="bg-emerald-600 p-8 rounded-[2.5rem] text-white italic shadow-xl relative overflow-hidden h-full flex flex-col justify-between">
              <div className="relative z-10">
                 <h3 className="text-[11px] font-black uppercase tracking-widest mb-10 border-b border-white/10 pb-6 flex items-center gap-3">
                    <AlertCircle size={18} className="text-emerald-100" />
                    Strict Validation
                 </h3>
                 <div className="space-y-10">
                    <p className="text-[10px] font-bold text-emerald-100 uppercase leading-relaxed tracking-widest italic">
                       The system requires the Receipt Amount to EXACTLY MATCH the Declared Amount to ensure total fiscal compliance.
                    </p>
                    <p className="text-[10px] font-bold text-emerald-100 uppercase leading-relaxed tracking-widest italic">
                       Partial payments are not permitted in this protocol.
                    </p>
                 </div>
              </div>
              <div className="absolute -bottom-10 -right-10 p-8 opacity-[0.1]">
                 <CheckCircle2 size={200} />
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}
