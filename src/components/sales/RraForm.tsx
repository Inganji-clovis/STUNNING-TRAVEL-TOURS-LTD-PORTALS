"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import { 
  Plus, 
  X, 
  ShieldCheck, 
  Upload, 
  FileText, 
  Calendar, 
  Save, 
  Briefcase 
} from "lucide-react";
import { archiveRraFile } from "@/lib/rra-actions";

export default function RraForm() {
  const router = useRouter();
  const [isOpen, setIsOpen] = useState(false);
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await archiveRraFile(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      setIsOpen(false);
      router.refresh();
    } else {
      toast.error(result.message);
    }
  }

  return (
    <>
      <button 
        onClick={() => setIsOpen(true)}
        className="flex items-center gap-3 px-8 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all italic"
      >
        <Plus size={16} /> Vault New Document
      </button>

      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 font-outfit uppercase font-black italic">
          <div className="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
              <div className="flex items-center gap-4 text-slate-900">
                <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-200">
                  <Briefcase size={24} />
                </div>
                <div>
                  <h2 className="text-2xl font-black uppercase tracking-tight italic uppercase">RRA Vault Archival</h2>
                  <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 italic uppercase">Phase 1: Secure Fiscal Documentation</p>
                </div>
              </div>
              <button onClick={() => setIsOpen(false)} className="p-2 hover:bg-slate-100 rounded-full transition-colors">
                <X size={20} className="text-slate-300" />
              </button>
            </div>

            <form action={handleSubmit} className="p-8 space-y-8 overflow-y-auto italic font-black">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Document Series (Type)</label>
                  <select name="type" required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs shadow-inner">
                    <option value="VAT">VAT Declaration</option>
                    <option value="PAYE">PAYE / Payroll Tax</option>
                    <option value="RSSB">RSSB Compliance</option>
                    <option value="CIT">Corporate Income Tax</option>
                    <option value="OTHER">Administrative Legal</option>
                  </select>
                </div>

                <div className="space-y-3">
                   <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Reporting Month</label>
                   <select name="month" required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs">
                      {["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"].map(m => (
                        <option key={m} value={m}>{m}</option>
                      ))}
                   </select>
                </div>

                <div className="space-y-3">
                   <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Fiscal Year</label>
                   <input type="number" name="year" defaultValue={new Date().getFullYear()} required className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>

                <div className="space-y-3">
                   <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 italic">Internal Remark</label>
                   <input name="description" placeholder="Ex: Q3 Declaration Proof" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>
              </div>

              <div className="space-y-3">
                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1 italic">Document File (PDF / Scan)</label>
                <div className="relative group/file">
                  <input type="file" name="file" required className="absolute inset-0 opacity-0 cursor-pointer z-10" />
                  <div className="border-2 border-dashed border-slate-200 rounded-[2rem] p-10 flex flex-col items-center gap-4 group-hover/file:border-slate-900 transition-all bg-slate-50/50">
                    <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-slate-300 group-hover/file:text-slate-900 shadow-sm transition-colors">
                      <Upload size={24} />
                    </div>
                    <div className="text-center">
                      <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest">Select Source File</p>
                      <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Archived securely on Google Drive</p>
                    </div>
                  </div>
                </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldCheck size={16} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Cryptographic Archival Protocol</span>
                 </div>
                 <button 
                  disabled={isPending}
                  className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95 disabled:opacity-50"
                 >
                    {isPending ? "Configuring Vault..." : <><Save size={18} /> Seal Document</>}
                 </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </>
  );
}
