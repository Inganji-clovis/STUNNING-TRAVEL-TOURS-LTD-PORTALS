"use client";

import { useState } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { toast } from "sonner";
import { 
  ArrowLeft, 
  Plus, 
  ShieldCheck, 
  Upload, 
  FileText, 
  DollarSign,
  Briefcase,
  History,
  Save,
  Info
} from "lucide-react";
import Link from "next/link";
import { declareTax } from "@/lib/tax-actions";

export default function CreateTaxDeclarationPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const type = searchParams.get("type") || "pension";
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await declareTax(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      router.push(`/sales/tax?type=${type}`);
      router.refresh();
    } else {
      toast.error(result.message);
    }
  }

  return (
    <div className="max-w-4xl mx-auto space-y-8 pb-20 font-outfit uppercase font-black italic">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href={`/sales/tax?type=${type}`} className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">{type.toUpperCase()} Declaration</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic">Phase 1: Fiscal Liability Recording</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2">
           <form action={handleSubmit} className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm space-y-8">
              <input type="hidden" name="type" value={type} />
              
              <div className="space-y-3">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">RRA Declaration Number</label>
                 <div className="relative">
                    <History className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="declaration_number" required placeholder="Ex: 567891234..." className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 text-xs focus:ring-2 focus:ring-slate-900/20 transition-all placeholder:italic" />
                 </div>
              </div>

              <div className="space-y-3">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Total Amount Declared (RWF)</label>
                 <div className="relative">
                    <DollarSign className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="declared_amount" type="number" step="0.01" required placeholder="0.00" className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 text-xs focus:ring-2 focus:ring-slate-900/20 transition-all" />
                 </div>
              </div>

              <div className="space-y-3">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Declaration Document (PDF)</label>
                 <div className="relative group/file">
                    <input type="file" name="declaration_file" required className="absolute inset-0 opacity-0 cursor-pointer z-10" />
                    <div className="border-2 border-dashed border-slate-200 rounded-[2rem] p-10 flex flex-col items-center gap-4 group-hover/file:border-slate-400 transition-all bg-slate-50/50">
                       <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 group-hover/file:text-slate-900 shadow-sm transition-colors">
                          <Upload size={24} />
                       </div>
                       <div className="text-center">
                          <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest italic">Upload Declaration PDF</p>
                          <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic">Archived securely for RRA compliance</p>
                       </div>
                    </div>
                 </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldCheck size={16} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Cloud Integrity Active</span>
                 </div>
                 <button 
                  disabled={isPending}
                  className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95 disabled:opacity-50 font-black uppercase"
                 >
                    {isPending ? "ARCHIVING..." : <><Save size={18} /> Commit Declaration</>}
                 </button>
              </div>
           </form>
        </div>

        <div className="space-y-8">
           <div className="bg-slate-900 p-8 rounded-[2.5rem] text-white italic shadow-xl relative overflow-hidden h-full flex flex-col justify-between">
              <div className="relative z-10">
                 <h3 className="text-[11px] font-black uppercase tracking-widest mb-10 border-b border-white/5 pb-6 flex items-center gap-3">
                    <Info size={18} className="text-blue-400" />
                    Protocol Rules
                 </h3>
                 <div className="space-y-10">
                    <div>
                       <p className="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3 italic">Phase 1: Declaration</p>
                       <p className="text-[10px] font-bold text-slate-400 uppercase leading-relaxed tracking-widest italic">
                          Record the amount as declared to RRA. This creates a liability record in the system.
                       </p>
                    </div>
                    <div>
                       <p className="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-3 italic">Phase 2: Payment</p>
                       <p className="text-[10px] font-bold text-slate-400 uppercase leading-relaxed tracking-widest italic">
                          Once payment is made, you must upload the proof (receipt) to clear the liability status.
                       </p>
                    </div>
                 </div>
              </div>
              <div className="absolute -bottom-10 -right-10 p-8 opacity-[0.03]">
                 <FileText size={200} />
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}
