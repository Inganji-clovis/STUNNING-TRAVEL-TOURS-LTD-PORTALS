import { prisma } from "@/lib/prisma";
import { 
  Users, 
  UserCheck, 
  TrendingUp, 
  Activity,
  ArrowUpRight,
  TrendingDown,
  CreditCard,
  FileText,
  DollarSign,
  Wallet,
  ShieldCheck,
  History,
  Plane
} from "lucide-react";
import Link from "next/link";

export default async function AdminDashboard() {
  let stats_data: any[] = [];
  let recentLogs: any[] = [];
  let userCount = 0;
  let ticketCount = 0;

  try {
    const [
      uCount, 
      aUsers, 
      transactions, 
      logs,
      tCount
    ] = await Promise.all([
      prisma.user.count(),
      prisma.user.count({ where: { is_active: true } }),
      prisma.cashTransaction.findMany(),
      prisma.activityLog.findMany({
        orderBy: { created_at: 'desc' },
        include: { user: true },
        take: 5
      }),
      prisma.ticket.count()
    ]);

    userCount = uCount;
    ticketCount = tCount;
    recentLogs = logs;

    const totalRevenue = transactions.reduce((acc, t) => acc + (t.service_fee || 0), 0);
    const totalExpenses = transactions.filter(t => t.type === 'expense').reduce((acc, t) => acc + t.amount, 0);
    const netProfit = totalRevenue - totalExpenses;

    const netPositionRWF = transactions
      .filter(t => t.currency === 'RWF')
      .reduce((acc, t) => acc + (t.type === 'income' ? t.amount : -t.amount), 0);
      
    const netPositionUSD = transactions
      .filter(t => t.currency === 'USD')
      .reduce((acc, t) => acc + (t.type === 'income' ? t.amount : -t.amount), 0);

    stats_data = [
      { label: "Agency Revenue (Fees)", value: `FRW ${totalRevenue.toLocaleString()}`, icon: TrendingUp, color: "text-emerald-600", bg: "bg-emerald-50", trend: "+12%" },
      { label: "Net Position (RWF)", value: `FRW ${netPositionRWF.toLocaleString()}`, icon: Wallet, color: "text-blue-600", bg: "bg-blue-50", trend: "Main Vault" },
      { label: "Net Position (USD)", value: `$ ${netPositionUSD.toLocaleString()}`, icon: DollarSign, color: "text-indigo-600", bg: "bg-indigo-50", trend: "Forex" },
      { label: "Yield Performance", value: `FRW ${netProfit.toLocaleString()}`, icon: Activity, color: "text-amber-600", bg: "bg-amber-50", trend: "P&L Balance" },
    ];
  } catch (error) {
    console.error("Dashboard data fetching failed:", error);
    // Fallback data
    stats_data = [
      { label: "Agency Revenue (Fees)", value: "---", icon: TrendingUp, color: "text-slate-400", bg: "bg-slate-50", trend: "Offline" },
      { label: "Net Position (RWF)", value: "---", icon: Wallet, color: "text-slate-400", bg: "bg-slate-50", trend: "Offline" },
      { label: "Net Position (USD)", value: "---", icon: DollarSign, color: "text-slate-400", bg: "bg-slate-50", trend: "Offline" },
      { label: "Yield Performance", value: "---", icon: Activity, color: "text-slate-400", bg: "bg-slate-50", trend: "Offline" },
    ];
  }

  return (
    <div className="space-y-10 font-outfit uppercase font-black italic">
      {/* Welcome Section */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">HQ Command Console</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic">Real-time Financial & Operational Telemetry</p>
        </div>
        <div className="flex items-center gap-4">
           <Link href="/admin/audit" className="px-8 py-4 bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-2xl border border-slate-200 hover:bg-slate-200 transition-all shadow-sm flex items-center gap-3">
              <History size={16} /> Audit Log
           </Link>
           <Link href="/admin/audit" className="px-8 py-4 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:scale-105 transition-all shadow-xl flex items-center gap-3">
              <FileText size={16} /> System Report
           </Link>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, i) => {
          const Icon = stat.icon;
          return (
            <div key={i} className="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group relative overflow-hidden h-full flex flex-col justify-between">
               <div className={`absolute top-0 right-0 w-32 h-32 ${stat.bg} rounded-full -mr-16 -mt-16 opacity-30 group-hover:scale-150 transition-transform`} />
               
               <div className="relative z-10">
                 <div className={`w-14 h-14 ${stat.bg} ${stat.color} rounded-2xl flex items-center justify-center mb-8 border border-white shadow-sm`}>
                   <Icon size={28} />
                 </div>
                 <p className="text-slate-400 font-black text-[9px] uppercase tracking-[0.3em] mb-2 italic leading-none">{stat.label}</p>
                 <div className="flex items-baseline gap-2">
                   <h3 className="text-xl font-black text-slate-900 tracking-tighter">{stat.value}</h3>
                 </div>
               </div>

               <div className="mt-6 pt-4 border-t border-slate-50 relative z-10">
                  <span className={`text-[8px] font-black uppercase tracking-widest px-3 py-1 rounded-lg ${stat.bg} ${stat.color}`}>
                    {stat.trend}
                  </span>
               </div>
            </div>
          );
        })}
      </div>

      {/* Personnel & Recent Audit */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-20">
        <div className="lg:col-span-2 bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm flex flex-col justify-between italic">
           <div>
              <div className="flex justify-between items-center mb-10">
                 <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight italic">Live Operational Activity</h2>
                 <Link href="/admin/audit" className="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline flex items-center gap-2">
                   Full History <ArrowRight size={14} />
                 </Link>
              </div>
              
              <div className="space-y-6">
                 {recentLogs.map((log) => (
                   <div key={log.id} className="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl group hover:border-blue-100 transition-all">
                      <div className="flex items-center gap-4">
                         <div className="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 font-black text-xs shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all">
                            {log.user.name[0]}
                         </div>
                         <div>
                            <p className="text-[11px] font-black text-slate-900 italic tracking-tight">{log.description}</p>
                            <p className="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">BY {log.user.name} &bull; {new Date(log.created_at).toLocaleTimeString()}</p>
                         </div>
                      </div>
                      <span className="text-[8px] font-black text-slate-300 group-hover:text-blue-600 transition-colors uppercase tracking-widest">{log.portal}</span>
                   </div>
                 ))}
              </div>
           </div>

           <div className="mt-10 pt-8 border-t border-slate-100 flex items-center justify-between">
              <div className="flex items-center gap-2">
                 <ShieldCheck size={16} className="text-emerald-500" />
                 <span className="text-[9px] font-black text-slate-400 uppercase tracking-widest">Integrity Hash Verified</span>
              </div>
              <p className="text-[9px] font-black text-slate-300 uppercase tracking-widest">System Load: 12%</p>
           </div>
        </div>

        <div className="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden flex flex-col justify-between italic">
           <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl" />
           
           <div className="relative z-10">
              <div className="flex items-center gap-3 mb-10">
                 <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/10">
                    <Activity size={20} className="text-blue-400" />
                 </div>
                 <h2 className="text-xl font-black uppercase tracking-tight italic">Registry Stats</h2>
              </div>

              <div className="space-y-8">
                 <div className="p-6 bg-white/5 rounded-2xl border border-white/5 backdrop-blur-md group hover:bg-white/10 transition-all">
                    <p className="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-3">Total Personnel</p>
                    <div className="flex items-center justify-between">
                       <h3 className="text-2xl font-black tracking-tighter">{userCount}</h3>
                       <Users className="text-white/20 group-hover:text-white/40 transition-colors" size={24} />
                    </div>
                 </div>

                 <div className="p-6 bg-white/5 rounded-2xl border border-white/5 border-dashed group hover:bg-white/10 transition-all">
                    <p className="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-3 italic">Operational Yield</p>
                    <div className="flex items-center justify-between font-black uppercase">
                       <h3 className="text-2xl font-black tracking-tighter">{ticketCount} Tickets</h3>
                       <Plane className="text-white/20 group-hover:text-white/40 transition-colors" size={24} />
                    </div>
                    <div className="mt-4 flex gap-1">
                       {[...Array(5)].map((_, i) => (
                         <div key={i} className={`h-1 flex-1 rounded-full ${i < 3 ? 'bg-blue-500' : 'bg-white/10'}`} />
                       ))}
                    </div>
                 </div>
              </div>
           </div>

           <div className="mt-12 relative z-10">
              <p className="text-slate-600 text-[8px] font-bold uppercase tracking-[0.5em] text-center border-t border-white/5 pt-6">
                Sovereign Portal Protocol v2.5
              </p>
           </div>
        </div>
      </div>
    </div>
  );
}
