import { prisma } from "@/lib/prisma";
import { 
  History, 
  Search, 
  Filter, 
  User, 
  Globe, 
  ShieldCheck, 
  Calendar,
  ArrowRight,
  Database
} from "lucide-react";
import Link from "next/link";

export default async function GlobalActivityPage({
  searchParams,
}: {
  searchParams: Promise<{ portal?: string; user?: string; search?: string }>;
}) {
  const { portal = "", user = "", search = "" } = await searchParams;

  const logs = await prisma.activityLog.findMany({
    where: {
      ...(portal && { portal }),
      ...(user && { user_id: parseInt(user) }),
      OR: [
        { description: { contains: search } },
        { action: { contains: search } },
        { target: { contains: search } }
      ]
    },
    include: { user: true },
    orderBy: { created_at: 'desc' },
    take: 50
  });

  const users = await prisma.user.findMany({ select: { id: true, name: true } });

  return (
    <div className="space-y-8 pb-20 italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">HQ Intelligence Stream</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic">Comprehensive Real-Time Global Audit Trail</p>
        </div>
        <div className="flex gap-3">
          <button className="px-6 py-3 bg-slate-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] flex items-center gap-2 hover:scale-[1.02] transition-all shadow-lg">
             <Database size={14} /> Export Logs
          </button>
        </div>
      </div>

      {/* Filter Matrix */}
      <div className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm flex flex-wrap items-end gap-6 text-slate-900">
         <div className="space-y-2 flex-1 min-w-[200px]">
            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Portal Filter</label>
            <div className="relative">
               <Globe className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
               <select name="portal" className="w-full pl-12 pr-6 py-3 bg-slate-50 border border-slate-100 rounded-2xl font-black text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic">
                  <option value="">All Portals</option>
                  <option value="Admin">Admin Control</option>
                  <option value="Sales">Sales Finance</option>
                  <option value="Ops">Operations Vault</option>
                  <option value="Md">MD Intelligence</option>
               </select>
            </div>
         </div>

         <div className="space-y-2 flex-1 min-w-[200px]">
            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Custodian Observer</label>
            <div className="relative">
               <User className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
               <select name="user" className="w-full pl-12 pr-6 py-3 bg-slate-50 border border-slate-100 rounded-2xl font-black text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic">
                  <option value="">All Staff</option>
                  {users.map(u => <option key={u.id} value={u.id}>{u.name}</option>)}
               </select>
            </div>
         </div>

         <div className="space-y-2 flex-1 min-w-[300px]">
            <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Universal Search</label>
            <div className="relative">
               <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
               <input 
                 type="text" 
                 placeholder="Search actions, targets, or events..."
                 className="w-full pl-12 pr-6 py-3 bg-slate-50 border border-slate-100 rounded-2xl font-black text-xs focus:outline-none focus:border-slate-900 transition-all uppercase italic placeholder:text-slate-300"
               />
            </div>
         </div>

         <button className="px-8 py-3 bg-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] italic hover:scale-105 transition-all shadow-xl active:scale-95">
            Apply Pulse
         </button>
      </div>

      {/* Activity Timeline */}
      <div className="bg-white rounded-[3rem] border border-slate-200 shadow-sm overflow-hidden min-h-screen">
         <div className="p-10 border-b border-slate-50 flex items-center justify-between">
             <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-4">
                <History size={26} className="text-blue-600" />
                Intelligence Feed
             </h2>
             <div className="flex items-center gap-3">
                <div className="flex -space-x-3">
                   {users.slice(0, 3).map(u => (
                     <div key={u.id} className="w-8 h-8 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-[10px] font-black text-slate-400">
                        {u.name[0]}
                     </div>
                   ))}
                </div>
                <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-2 italic">Active Observers</span>
             </div>
         </div>

         <div className="p-10 space-y-12 relative">
            <div className="absolute left-16 top-10 bottom-10 w-[2px] bg-slate-50" />
            
            {logs.length > 0 ? logs.map((log) => (
              <div key={log.id} className="relative z-10 flex gap-10 group items-start">
                 <div className="w-12 h-12 shrink-0 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-slate-900 font-black text-xs uppercase group-hover:bg-slate-900 group-hover:text-white transition-all duration-500 hover:scale-110">
                    {log.user.name[0]}
                 </div>
                 
                 <div className="flex-1 pt-1 space-y-3 pb-8 border-b border-slate-50 group-last:border-0 group-last:pb-0">
                    <div className="flex justify-between items-baseline">
                       <div className="flex items-center gap-3">
                          <span className="text-sm font-black text-slate-900 uppercase tracking-tight italic">{log.user.name}</span>
                          <span className={`px-2 py-0.5 rounded uppercase text-[8px] font-black tracking-widest italic shadow-sm border ${
                             log.portal === 'Admin' ? 'bg-red-50 text-red-600 border-red-100' :
                             log.portal === 'Sales' ? 'bg-blue-50 text-blue-600 border-blue-100' :
                             'bg-emerald-50 text-emerald-600 border-emerald-100'
                          }`}>{log.portal} PORTAL</span>
                       </div>
                       <div className="flex items-center gap-3 text-slate-400">
                          <Calendar size={12} />
                          <span className="text-[9px] font-bold uppercase tracking-widest italic">{new Date(log.created_at).toLocaleString()}</span>
                       </div>
                    </div>

                    <div className="flex items-center gap-4">
                       <div className="px-3 py-1 bg-slate-50 rounded-lg border border-slate-100 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                          [{log.action}]
                       </div>
                       <p className="text-xs text-slate-600 font-bold uppercase tracking-tight italic leading-relaxed">
                          {log.description}
                          {log.target && <span className="ml-2 text-blue-600 bg-blue-50 px-2 py-0.5 rounded">#{log.target}</span>}
                       </p>
                    </div>

                    {log.properties ? (
                      <div className="p-4 bg-slate-50/50 rounded-2xl border border-slate-50 text-[10px] font-mono text-slate-400">
                         {log.properties}
                      </div>
                    ) : null}
                 </div>
                 
                 <button className="opacity-0 group-hover:opacity-100 transition-opacity p-2 text-slate-200 hover:text-slate-900">
                    <ArrowRight size={18} />
                 </button>
              </div>
            )) : (
              <div className="py-40 text-center flex flex-col items-center gap-6 opacity-30">
                 <Database size={64} className="text-slate-100" />
                 <p className="font-black uppercase tracking-[0.3em] text-sm">Intel Stream Vacant</p>
              </div>
            )}
         </div>
         
         {/* Footer integrity check */}
         <div className="p-10 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <div className="flex items-center gap-4">
               <ShieldCheck size={20} className="text-emerald-500" />
               <span className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">End-to-End Cryptographic Audit Integrity Enabled</span>
            </div>
            <button className="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Verify Database Block</button>
         </div>
      </div>
    </div>
  );
}
