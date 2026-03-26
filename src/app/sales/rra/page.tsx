import { prisma } from "@/lib/prisma";
import { 
  Briefcase, 
  Search, 
  Filter, 
  Download, 
  FileText, 
  Calendar, 
  ShieldCheck, 
  History,
  HardDrive
} from "lucide-react";
import Link from "next/link";
import RraForm from "@/components/sales/RraForm";

export default async function RraVaultPage() {
  const documents = await prisma.rraFile.findMany({
    orderBy: { created_at: 'desc' },
    include: { uploader: true }
  });

  return (
    <div className="space-y-8 font-outfit uppercase font-black italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic font-black uppercase">RRA Compliance Vault</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic uppercase font-black uppercase">Sovereign Archival & Audit Repository</p>
        </div>
        <RraForm />
      </div>

      {/* Grid View */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        {documents.length > 0 ? documents.map((doc) => (
          <div key={doc.id} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden flex flex-col justify-between min-h-[400px]">
             <div>
                <div className="flex justify-between items-start mb-6">
                   <div className="w-14 h-14 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center border border-slate-100 shadow-sm group-hover:bg-slate-900 group-hover:text-white transition-all">
                      <FileText size={28} />
                   </div>
                   <div className="text-right">
                      <div className="flex items-center gap-2 justify-end mb-1">
                         <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
                         <p className="text-[9px] font-black text-slate-400 uppercase tracking-widest italic">Vaulted</p>
                      </div>
                      <p className="text-[10px] font-bold text-slate-900 tracking-widest">SER: {doc.id.toString().padStart(5, '0')}</p>
                   </div>
                </div>

                <div className="space-y-6">
                   <div>
                      <h3 className="text-xl font-black text-slate-900 uppercase tracking-tight italic leading-none">{doc.type}</h3>
                      <p className="text-[10px] font-black text-blue-600 uppercase tracking-[0.2em] mt-3 italic flex items-center gap-2">
                        <Calendar size={12} /> {doc.month} {doc.year}
                      </p>
                   </div>

                   <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed italic line-clamp-2">
                      {doc.description || 'NO_METADATA_EXTRACTED'}
                   </p>

                   <div className="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center gap-4">
                      <div className="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-slate-400 shadow-sm">
                         <ShieldCheck size={18} />
                      </div>
                      <div>
                         <p className="text-[8px] font-black text-slate-500 uppercase tracking-widest italic leading-none">Archived By</p>
                         <p className="text-[10px] font-black text-slate-900 mt-1">{doc.uploader.name}</p>
                      </div>
                   </div>
                </div>
             </div>

             <div className="flex items-center justify-between pt-8 mt-8 border-t border-slate-50">
                <div className="flex items-center gap-2">
                   <span className="text-[8px] font-black text-slate-400 uppercase tracking-widest italic">{new Date(doc.created_at).toLocaleDateString()}</span>
                </div>
                <Link 
                  href={`/api/vault/rra/${doc.id}`}
                  target="_blank"
                  className="px-6 py-3 bg-slate-900 text-white rounded-xl text-[9px] font-black uppercase tracking-widest italic flex items-center gap-3 hover:scale-105 transition-all shadow-xl"
                >
                  <Download size={14} /> GET_LEDGER
                </Link>
             </div>
          </div>
        )) : (
          <div className="col-span-full py-40 bg-white rounded-[2.5rem] border-2 border-slate-100 border-dashed text-center flex flex-col items-center gap-6 shadow-inner">
             <div className="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                <HardDrive size={56} />
             </div>
             <div>
                <p className="text-slate-900 font-black text-2xl uppercase tracking-tighter italic">RRA Archive Protocol Null</p>
                <p className="text-slate-400 font-bold text-xs uppercase tracking-[0.3em] mt-2 italic shadow-sm">Upload fiscal documents to initialize vault registry</p>
             </div>
          </div>
        )}
      </div>
    </div>
  );
}
