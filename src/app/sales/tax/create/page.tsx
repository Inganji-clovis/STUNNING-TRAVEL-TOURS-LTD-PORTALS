import { prisma } from "@/lib/prisma";
import { 
  Building2, 
  FileText, 
  ArrowLeft, 
  ShieldCheck, 
  ShieldAlert,
  Save,
  Info
} from "lucide-react";
import Link from "next/link";
import { declareTax } from "@/lib/tax-actions";

export default async function CreateTaxDeclarationPage({
  searchParams,
}: {
  searchParams: Promise<{ type?: string }>;
}) {
  const { type = "pension" } = await searchParams;

  return (
    <div className="max-w-4xl mx-auto space-y-8">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href={`/sales/tax?type=${type}`} className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Initiate Compliance</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic">Phase 1: Tax Declaration & Assessment</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Form Column */}
        <div className="lg:col-span-2">
          <form action={declareTax} className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm space-y-8 italic">
             <input type="hidden" name="type" value={type} />
             
             <div className="grid grid-cols-1 gap-8">
                <div className="space-y-3">
                   <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Declaration Number</label>
                   <div className="relative">
                      <FileText className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                      <input 
                        name="declaration_number"
                        type="text" 
                        required
                        placeholder="e.g., RSSB-2026-03-XXXX"
                        className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase placeholder:text-slate-300 italic"
                      />
                   </div>
                </div>

                <div className="space-y-3">
                   <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Declared Amount (RWF)</label>
                   <div className="relative">
                      <Building2 className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                      <input 
                        name="declared_amount"
                        type="number" 
                        required
                        placeholder="0.00"
                        className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all italic placeholder:text-slate-300"
                      />
                   </div>
                </div>

                <div className="space-y-3">
                   <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Evidence of Declaration (PDF/Image)</label>
                   <div className="p-10 border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50 flex flex-col items-center justify-center gap-4 group hover:border-slate-900 transition-all cursor-pointer relative overflow-hidden">
                      <input type="file" name="declaration_file" className="absolute inset-0 opacity-0 cursor-pointer" />
                      <div className="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-300 group-hover:text-slate-900 transition-all border border-slate-100">
                         <ShieldCheck size={24} />
                      </div>
                      <div className="text-center">
                         <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest italic leading-relaxed">Click to upload declaration proof</p>
                         <p className="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Maximum file size: 10MB</p>
                      </div>
                   </div>
                </div>
             </div>

             <div className="pt-6 border-t border-slate-100 flex items-center justify-between">
                <div className="flex items-center gap-2">
                   <ShieldAlert size={14} className="text-amber-500" />
                   <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest leading-none">Fiscal integrity verified before submission</span>
                </div>
                <button className="px-10 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-xl active:scale-95">
                   <Save size={16} /> File Declaration
                </button>
             </div>
          </form>
        </div>

        {/* Info Column */}
        <div className="space-y-8">
           <div className="bg-slate-900 p-8 rounded-[2rem] text-white italic shadow-xl relative overflow-hidden">
              <div className="relative z-10">
                 <div className="flex items-center gap-3 mb-6">
                    <Info size={20} className="text-blue-400" />
                    <h3 className="text-sm font-black uppercase tracking-widest">{type.toUpperCase()} PROTOCOL</h3>
                 </div>
                 <p className="text-xs font-bold text-slate-400 uppercase tracking-widest leading-[1.8] mb-6">
                    Compliance is a two-phase operation. Declaring records the liability in the system. Payment proof will be required in Phase 2 to achieve completion status.
                 </p>
                 <div className="space-y-4">
                    <div className="flex items-center gap-3 text-[10px] font-black text-blue-400 uppercase tracking-widest border-b border-white/5 pb-3">
                       <span className="w-5 h-5 rounded-full bg-blue-400 text-slate-900 flex items-center justify-center text-[8px]">1</span>
                       Field Declaration
                    </div>
                    <div className="flex items-center gap-3 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                       <span className="w-5 h-5 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center text-[8px]">2</span>
                       Settle Payment
                    </div>
                 </div>
              </div>
              <div className="absolute top-0 right-0 p-8 opacity-10">
                 <Building2 size={120} />
              </div>
           </div>

           <div className="bg-white p-8 rounded-[2rem] border border-slate-200 italic">
              <h4 className="text-[10px] font-black text-slate-900 uppercase tracking-widest mb-4 flex items-center gap-2">
                 <ShieldCheck size={14} className="text-emerald-500" />
                 Compliance Rules
              </h4>
              <ul className="space-y-3">
                 {[
                   'Amounts must match RRA/RSSB portals exactly',
                   'Files must be legible digital copies',
                   'Declaration numbers are unique per year'
                 ].map((rule, i) => (
                   <li key={i} className="flex gap-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                      <span className="text-slate-900 opacity-20">•</span>
                      {rule}
                   </li>
                 ))}
              </ul>
           </div>
        </div>
      </div>
    </div>
  );
}
