import { prisma } from "@/lib/prisma";
import { 
  Plus, 
  Search, 
  ShieldCheck, 
  FileText, 
  Download,
  Calendar,
  Building2
} from "lucide-react";
import Link from "next/link";

export default async function EbmRegistryPage() {
  const ebms = await prisma.ebm.findMany({
    orderBy: { created_at: 'desc' },
    include: { uploader: true }
  });

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">EBM Registry</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Electronic Billing Machine Archives</p>
        </div>
        <button className="flex items-center gap-3 px-8 py-4 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-blue-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
          <Plus size={16} /> Archive New EBM
        </button>
      </div>

      {/* Grid View */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {ebms.length > 0 ? ebms.map((ebm) => (
          <div key={ebm.id} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
             <div className="flex justify-between items-start mb-6">
                <div className="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center border border-blue-100 shadow-sm">
                   <ShieldCheck size={24} />
                </div>
                <div className="text-right">
                   <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Receipt Date</p>
                   <p className="text-xs font-bold text-slate-900">{new Date(ebm.date).toLocaleDateString()}</p>
                </div>
             </div>

             <div className="space-y-4">
                <div>
                   <h3 className="text-lg font-black text-slate-900 uppercase tracking-tight truncate">{ebm.company_name}</h3>
                   <p className="text-[10px] font-bold text-blue-600 uppercase tracking-[0.2em] mt-1 italic">TIN: {ebm.tin_number}</p>
                </div>

                <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                   <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 italic">Financial Value</p>
                   <p className="text-xl font-black text-slate-900 tracking-tight">RWF {Number(ebm.amount_rwf).toLocaleString()}</p>
                </div>

                <div className="flex items-center justify-between pt-2">
                   <div className="flex items-center gap-2">
                      <div className="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[8px] font-black text-slate-500 uppercase">
                         {ebm.uploader.name[0]}
                      </div>
                      <span className="text-[9px] font-bold text-slate-400 uppercase tracking-widest italic truncate max-w-[100px]">
                         By {ebm.uploader.name}
                      </span>
                   </div>
                   <button className="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                      <Download size={18} />
                   </button>
                </div>
             </div>
          </div>
        )) : (
          <div className="col-span-full py-20 bg-white rounded-[2.5rem] border border-slate-200 border-dashed text-center flex flex-col items-center gap-4 opacity-40">
             <ShieldCheck size={48} className="text-slate-300" />
             <p className="text-slate-400 font-black text-sm uppercase tracking-widest italic">No archived EBM receipts found</p>
          </div>
        )}
      </div>
    </div>
  );
}
