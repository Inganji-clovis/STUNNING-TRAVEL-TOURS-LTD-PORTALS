import { prisma } from "@/lib/prisma";
import { 
  FolderLock, 
  Search, 
  Calendar, 
  FileBox, 
  Download, 
  Trash2,
  HardDriveDownload,
  ShieldCheck,
  Plus
} from "lucide-react";
import Link from "next/link";

export default async function RraVaultPage({
  searchParams,
}: {
  searchParams: Promise<{ type?: string; year?: string; month?: string }>;
}) {
  const { type = "", year = "", month = "" } = await searchParams;

  const files = await prisma.rraFile.findMany({
    where: {
      ...(type && { type }),
      ...(year && { year: parseInt(year) }),
      ...(month && { month }),
    },
    include: { uploader: true },
    orderBy: { created_at: 'desc' }
  });

  const years = Array.from({ length: 5 }, (_, i) => new Date().getFullYear() - i);
  const months = [
    "January", "February", "March", "April", "May", "June", 
    "July", "August", "September", "October", "November", "December"
  ];

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">RRA Document Vault</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic">Secure Fiscal Archiving System</p>
        </div>
        <Link href="/sales/rra/upload" className="px-6 py-3 bg-slate-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] flex items-center gap-2 hover:scale-[1.02] transition-all shadow-lg active:scale-[0.98]">
           <Plus size={14} /> Archive Document
        </Link>
      </div>

      {/* Filter Bar */}
      <div className="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm flex flex-wrap items-end gap-6">
        <div className="space-y-2 flex-1 min-w-[200px]">
          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Document Type</label>
          <select className="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic">
            <option value="">All Types</option>
            <option value="ebm">EBM Reports</option>
            <option value="ibm">IBM Reports</option>
            <option value="rra_monthly">RRA Monthly Files</option>
          </select>
        </div>
        
        <div className="space-y-2 w-32">
          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Year</label>
          <select className="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-xs focus:outline-none focus:border-slate-900 transition-all italic">
            {years.map(y => <option key={y} value={y}>{y}</option>)}
          </select>
        </div>

        <div className="space-y-2 w-48">
          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Month</label>
          <select className="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic">
            <option value="">All Months</option>
            {months.map(m => <option key={m} value={m}>{m}</option>)}
          </select>
        </div>

        <button className="px-8 py-3 bg-blue-50 text-blue-600 rounded-xl font-black uppercase tracking-widest text-[10px] italic hover:bg-blue-100 transition-all">
          Apply Filters
        </button>
      </div>

      {/* Grid of Documents */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {files.length > 0 ? files.map((file) => (
          <div key={file.id} className="bg-white rounded-[2rem] border border-slate-200 shadow-sm group hover:border-slate-400 transition-all overflow-hidden flex flex-col">
            <div className="p-8 pb-4">
              <div className="flex justify-between items-start mb-6">
                <div className={`p-3 rounded-2xl ${
                  file.type === 'ebm' ? 'bg-blue-50 text-blue-600' :
                  file.type === 'ibm' ? 'bg-amber-50 text-amber-600' :
                  'bg-emerald-50 text-emerald-600'
                }`}>
                  <FileBox size={24} />
                </div>
                <div className="flex gap-2">
                   <button className="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">
                      <Download size={14} />
                   </button>
                   <button className="w-8 h-8 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all">
                      <Trash2 size={14} />
                   </button>
                </div>
              </div>
              
              <h3 className="font-black text-slate-900 uppercase tracking-tight italic mb-1 truncate">{file.description || file.type.toUpperCase() + ' ARCHIVE'}</h3>
              <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4 italic">{file.month} {file.year}</p>
              
              <div className="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 mb-4">
                <div className="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[10px] font-black text-slate-900 shadow-sm">
                   {file.uploader.name[0]}
                </div>
                <div className="flex flex-col">
                   <span className="text-[9px] font-black text-slate-900 uppercase italic leading-none">{file.uploader.name}</span>
                   <span className="text-[8px] font-bold text-slate-400 uppercase tracking-widest italic mt-1">Custodian</span>
                </div>
              </div>
            </div>
            
            <div className="mt-auto px-8 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
               <div className="flex items-center gap-2">
                  <ShieldCheck size={12} className="text-emerald-500" />
                  <span className="text-[8px] font-black uppercase text-slate-400 tracking-widest italic">Encrypted Vault</span>
               </div>
               <span className="text-[9px] font-black text-slate-900 italic uppercase">{new Date(file.created_at).toLocaleDateString()}</span>
            </div>
          </div>
        )) : (
          <div className="col-span-full py-32 bg-slate-50/50 rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-4 text-center">
             <div className="w-16 h-16 rounded-full bg-white flex items-center justify-center text-slate-200 shadow-sm border border-slate-100">
                <FolderLock size={32} />
             </div>
             <div>
                <p className="text-sm font-black text-slate-400 uppercase tracking-[0.2em] italic">Vault is Empty</p>
                <p className="text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-1">No fiscal documents archived for this period</p>
             </div>
          </div>
        )}
      </div>

      {/* Storage Integrity */}
      <div className="bg-slate-900 p-10 rounded-[2.5rem] shadow-xl relative overflow-hidden text-white flex flex-col md:flex-row items-center justify-between gap-8 italic">
         <div className="relative z-10 flex items-center gap-6">
            <div className="w-16 h-16 rounded-[1.5rem] bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10">
               <HardDriveDownload size={32} />
            </div>
            <div>
               <h3 className="text-lg font-black uppercase tracking-tight">Cloud Redundancy Verification</h3>
               <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Storage status: SECURE & BACKED UP ON GOOGLE DRIVE CMD</p>
            </div>
         </div>
         <button className="relative z-10 px-8 py-3 bg-white text-slate-900 rounded-xl font-black uppercase tracking-widest text-[10px] hover:scale-105 transition-all shadow-lg active:scale-95">
             Run Integrity Check
         </button>
         <div className="absolute top-0 right-0 p-8 opacity-10">
            <FolderLock size={150} />
         </div>
      </div>
    </div>
  );
}
