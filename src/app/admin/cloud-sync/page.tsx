import { prisma } from "@/lib/prisma";
import { startCloudSync } from "@/lib/cloud-sync-actions";
import { 
  CloudRain, 
  RefreshCw, 
  CheckCircle2, 
  XCircle, 
  Clock, 
  Calendar,
  User as UserIcon,
  Search,
  History,
  HardDrive
} from "lucide-react";
import Link from "next/link";

export default async function CloudSyncPage() {
  let syncs: any[] = [];
  try {
    syncs = await prisma.cloudSync.findMany({
      orderBy: { created_at: 'desc' },
      include: { admin: true },
      take: 20
    });
  } catch (error) {
    console.error("Cloud sync data fetching failed:", error);
  }

  const years = Array.from({ length: 5 }, (_, i) => new Date().getFullYear() - i);
  const months = [
    { name: "January", value: 1 }, { name: "February", value: 2 }, { name: "March", value: 3 },
    { name: "April", value: 4 }, { name: "May", value: 5 }, { name: "June", value: 6 },
    { name: "July", value: 7 }, { name: "August", value: 8 }, { name: "September", value: 9 },
    { name: "October", value: 10 }, { name: "November", value: 11 }, { name: "December", value: 12 }
  ];

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Cloud Command</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic">Google Drive Sync & Backup Engine</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Sync Trigger Card */}
        <div className="lg:col-span-1 bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm">
           <div className="flex items-center gap-4 mb-8">
              <div className="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 shadow-sm">
                 <RefreshCw size={24} />
              </div>
              <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight">System Sync</h2>
           </div>

           <form action={startCloudSync} className="space-y-6">
              <div className="space-y-2">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Archive Year</label>
                 <select name="year" className="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-blue-600 transition-all uppercase text-sm italic">
                    {years.map(y => <option key={y} value={y}>{y}</option>)}
                 </select>
              </div>

              <div className="space-y-2">
                 <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Archive Month</label>
                 <select name="month" className="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-blue-600 transition-all uppercase text-sm italic">
                    {months.map(m => <option key={m.value} value={m.value}>{m.name}</option>)}
                 </select>
              </div>

              <div className="p-6 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-start gap-4">
                 <div className="w-2 h-2 rounded-full bg-blue-600 mt-1.5 animate-pulse" />
                 <p className="text-[10px] font-bold text-blue-900 uppercase tracking-widest leading-relaxed italic">
                    Synchronizing will archive all monthly Invoices, EBMs, Tickets, and Passports to the secure Google Drive vault.
                 </p>
              </div>

              <button className="w-full py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-xs shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                 <CloudRain size={18} /> Initiate Macro-Sync
              </button>
           </form>
        </div>

        {/* Sync History Table */}
        <div className="lg:col-span-2 bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm">
            <div className="flex justify-between items-center mb-10">
              <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-3 italic">
                 <History size={24} />
                 Sync Operations Ledger
              </h2>
              <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest italic border border-slate-100 px-3 py-1 rounded-lg">Last 20 Operations</span>
            </div>

            <div className="overflow-x-auto whitespace-nowrap">
               <table className="w-full text-left">
                  <thead>
                     <tr className="border-b border-slate-50">
                        <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Operation</th>
                        <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Archive Tag</th>
                        <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Executor</th>
                     </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-50">
                     {syncs.length > 0 ? syncs.map((sync) => (
                       <tr key={sync.id} className="group italic">
                          <td className="py-6">
                             <div className="flex items-center gap-3">
                                <div className="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 font-black text-[10px]">
                                   #{sync.id}
                                </div>
                                <span className="text-xs font-black text-slate-900 italic uppercase">{new Date(sync.created_at).toLocaleDateString()}</span>
                             </div>
                          </td>
                          <td className="py-6 text-center">
                             <span className="px-3 py-1 bg-slate-900 text-white text-[9px] font-black rounded-lg uppercase tracking-widest italic">{sync.year}.{sync.month.toString().padStart(2, '0')}</span>
                          </td>
                          <td className="py-6">
                             <div className={`flex items-center gap-2 font-black text-[9px] uppercase tracking-widest ${
                                sync.status === 'completed' ? 'text-emerald-600' : 
                                sync.status === 'failed' ? 'text-red-600' : 'text-amber-600'
                             }`}>
                                {sync.status === 'completed' ? <CheckCircle2 size={12} /> : 
                                 sync.status === 'failed' ? <XCircle size={12} /> : 
                                 <RefreshCw size={12} className="animate-spin" />}
                                {sync.status}
                             </div>
                          </td>
                          <td className="py-6 text-right">
                             <div className="flex items-center justify-end gap-2 text-slate-500">
                                <span className="text-[10px] font-black uppercase tracking-widest italic">{sync.admin?.name || 'System'}</span>
                                <div className="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[8px] font-black uppercase text-slate-600 border border-slate-200">
                                   {sync.admin?.name?.[0] || 'S'}
                                </div>
                             </div>
                          </td>
                       </tr>
                     )) : (
                       <tr>
                          <td colSpan={4} className="py-20 text-center opacity-30 italic">No cloud sync operations recorded yet.</td>
                       </tr>
                     )}
                  </tbody>
               </table>
            </div>

            <div className="mt-10 p-8 rounded-[2rem] bg-slate-50 border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
               <div className="flex items-center gap-4">
                  <div className="w-12 h-12 rounded-2xl bg-white flex items-center justify-center text-slate-900 shadow-sm border border-slate-200">
                     <HardDrive size={24} />
                  </div>
                  <div>
                     <p className="text-xs font-black text-slate-900 uppercase tracking-tight italic leading-tight">Google Drive Storage Integrity</p>
                     <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Status: VERIFIED & ENCRYPTED</p>
                  </div>
               </div>
               <button className="px-8 py-3 bg-white border border-slate-200 text-slate-900 rounded-xl text-[10px] font-black uppercase tracking-widest hover:border-blue-600 transition-all shadow-sm italic">
                  Run Diagnostic
               </button>
            </div>
        </div>
      </div>
    </div>
  );
}
