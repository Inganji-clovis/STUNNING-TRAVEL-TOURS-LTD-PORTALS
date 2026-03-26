import { prisma } from "@/lib/prisma";
import { 
  History, 
  Search, 
  Filter, 
  ShieldCheck, 
  User, 
  ArrowRight, 
  Activity,
  Calendar,
  Layers,
  HardDrive
} from "lucide-react";

export default async function AuditLogPage() {
  let logs: any[] = [];
  try {
    logs = await prisma.activityLog.findMany({
      orderBy: { created_at: 'desc' },
      include: { user: true },
      take: 100
    });
  } catch (error) {
    console.error("Audit log data fetching failed:", error);
  }

  return (
    <div className="space-y-8 font-outfit uppercase font-black italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic font-black uppercase">Global Activity Hub</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic uppercase font-black uppercase">Centralised operational ledger & audit protocol</p>
        </div>
        <div className="flex items-center gap-4">
           <div className="px-6 py-4 bg-white border border-slate-200 rounded-2xl flex items-center gap-4 shadow-sm">
              <div className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" />
              <span className="text-[10px] font-black text-slate-500 tracking-widest italic">REAL-TIME TELEMETRY ACTIVE</span>
           </div>
        </div>
      </div>

      {/* Audit Feed */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden whitespace-nowrap italic font-black uppercase">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-slate-50/50 border-b border-slate-100">
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Timestamp</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Operator</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Vector (Portal)</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Action Matrix</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Resource Target</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {logs.length > 0 ? logs.map((log) => (
                <tr key={log.id} className="hover:bg-slate-50/50 transition-all group">
                  <td className="px-10 py-8">
                     <div className="flex flex-col">
                        <span className="text-xs font-black text-slate-900 leading-none">{new Date(log.created_at).toLocaleTimeString()}</span>
                        <span className="text-[9px] font-bold text-slate-400 mt-2">{new Date(log.created_at).toLocaleDateString()}</span>
                     </div>
                  </td>
                  <td className="px-10 py-8">
                    <div className="flex items-center gap-3">
                       <div className="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xs shadow-xl">
                          {log.user.name[0]}
                       </div>
                       <div>
                          <p className="text-xs font-black text-slate-900">{log.user.name}</p>
                          <p className="text-[9px] font-bold text-slate-400 tracking-widest">UID: {log.user_id}</p>
                       </div>
                    </div>
                  </td>
                  <td className="px-10 py-8">
                    <span className={`px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border italic flex items-center gap-2 w-fit ${
                      log.portal === 'Sales' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                      log.portal === 'Operations' ? 'bg-blue-50 text-blue-600 border-blue-100' :
                      log.portal === 'Admin' ? 'bg-slate-900 text-white border-slate-800' :
                      'bg-purple-50 text-purple-600 border-purple-100'
                    }`}>
                      <Layers size={12} /> {log.portal}
                    </span>
                  </td>
                  <td className="px-10 py-8">
                    <div>
                       <p className="text-xs font-black text-slate-900 italic uppercase">{log.action}</p>
                       <p className="text-[9px] font-bold text-slate-400 tracking-widest leading-relaxed mt-1">{log.module}</p>
                    </div>
                  </td>
                  <td className="px-10 py-8">
                    <div className="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 shadow-inner group-hover:bg-white transition-all">
                       <Activity size={14} className="text-emerald-500" />
                       <p className="text-[10px] font-black text-slate-600 tracking-tight truncate max-w-[200px]">{log.description}</p>
                    </div>
                  </td>
                </tr>
              )) : (
                <tr>
                   <td colSpan={5} className="py-40 text-center opacity-30">
                      <div className="flex flex-col items-center gap-6">
                        <History size={64} className="text-slate-200" />
                        <p className="text-slate-400 font-black text-md uppercase tracking-[0.3em] italic">Operational archive is currently at zero</p>
                      </div>
                   </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
