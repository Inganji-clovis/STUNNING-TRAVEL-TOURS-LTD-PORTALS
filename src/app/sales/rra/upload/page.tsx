import { prisma } from "@/lib/prisma";
import { 
  Building2, 
  FileBox, 
  ArrowLeft, 
  ShieldCheck, 
  ShieldAlert,
  HardDriveUpload,
  Calendar,
  Layers,
  Search
} from "lucide-react";
import Link from "next/link";
import { archiveRraFile } from "@/lib/rra-actions";

export default async function UploadRraDocumentPage() {
  const years = Array.from({ length: 5 }, (_, i) => new Date().getFullYear() - i);
  const months = [
    "January", "February", "March", "April", "May", "June", 
    "July", "August", "September", "October", "November", "December"
  ];

  return (
    <div className="max-w-4xl mx-auto space-y-8">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href="/sales/rra" className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Vault Archiving</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic">Secure Fiscal Document Depository</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2">
           <form action={archiveRraFile} className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm space-y-8 italic">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Document Type</label>
                    <div className="relative">
                       <Layers className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <select name="type" required className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic">
                          <option value="ebm">EBM Monthly Report</option>
                          <option value="ibm">IBM Monthly Report</option>
                          <option value="rra_monthly">RRA Monthly Filing</option>
                       </select>
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Archive Segment (Month/Year)</label>
                    <div className="flex gap-4">
                       <select name="month" required className="flex-1 px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic">
                          {months.map(m => <option key={m} value={m}>{m}</option>)}
                       </select>
                       <select name="year" required className="w-32 px-5 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all italic">
                          {years.map(y => <option key={y} value={y}>{y}</option>)}
                       </select>
                    </div>
                 </div>

                 <div className="col-span-full space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Description / Notes</label>
                    <div className="relative">
                       <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="description"
                         type="text" 
                         placeholder="e.g., MARCH 2026 EBM CONSOLIDATED LOG"
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase placeholder:text-slate-300 italic"
                       />
                    </div>
                 </div>

                 <div className="col-span-full space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Fiscal Evidence</label>
                    <div className="p-16 border-2 border-dashed border-slate-200 rounded-[2.5rem] bg-slate-50 flex flex-col items-center justify-center gap-4 group hover:border-slate-900 transition-all cursor-pointer relative overflow-hidden">
                       <input type="file" name="file" required className="absolute inset-0 opacity-0 cursor-pointer" />
                       <div className="w-16 h-16 rounded-[1.5rem] bg-white shadow-md flex items-center justify-center text-slate-900 group-hover:bg-slate-900 group-hover:text-white transition-all border border-slate-100">
                          <HardDriveUpload size={32} />
                       </div>
                       <div className="text-center">
                          <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest italic leading-relaxed">Deploy document into vault</p>
                          <p className="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">PDF, Excel, or Zip Archive • MAX 20MB</p>
                       </div>
                    </div>
                 </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldCheck size={16} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic tracking-[0.2em]">End-to-End Vault Integrity Verified</span>
                 </div>
                 <button className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95">
                    <FileBox size={18} /> Commit to Archive
                 </button>
              </div>
           </form>
        </div>

        <div className="space-y-8">
           <div className="bg-emerald-600 p-8 rounded-[2rem] text-white italic shadow-xl relative overflow-hidden">
              <div className="relative z-10">
                 <h3 className="text-sm font-black uppercase tracking-widest mb-4">RRA VAULT PROTOCOL</h3>
                 <p className="text-[10px] font-bold text-emerald-100 uppercase tracking-widest leading-relaxed opacity-80">
                    The RRA Vault is a critical headquarters resource. All monthly EBM and IBM reports must be archived within 5 days of period close.
                 </p>
              </div>
              <div className="absolute top-0 right-0 p-8 opacity-20">
                 <ShieldCheck size={120} />
              </div>
           </div>
           
           <div className="bg-white p-8 rounded-[2rem] border border-slate-200 italic">
              <h4 className="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-6 border-b border-slate-50 pb-4">Required Archives</h4>
              <div className="space-y-6">
                 {[
                   { label: 'EBM Consolidated', icon: Layers },
                   { label: 'IBM Monthly', icon: Calendar },
                   { label: 'Monthly Declaration', icon: ShieldCheck }
                 ].map((item, i) => (
                   <div key={i} className="flex items-center gap-4">
                      <div className="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                         <item.icon size={16} />
                      </div>
                      <span className="text-[10px] font-black text-slate-900 uppercase italic">{item.label}</span>
                   </div>
                 ))}
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}
