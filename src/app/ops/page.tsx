import { prisma } from "@/lib/prisma";
import { 
  Users, 
  Building2, 
  Ticket, 
  FileText,
  Clock,
  Send,
  ArrowUpRight,
  TrendingUp,
  Inbox,
  Plus
} from "lucide-react";

export default async function OperationsDashboard() {
  const [clientCount, orgCount, ticketCount, docCount, dispatches] = await Promise.all([
    prisma.client.count(),
    prisma.organization.count(),
    prisma.ticket.count(),
    prisma.travelDocument.count(),
    prisma.mdDispatch.findMany({
      where: { status: 'pending' },
      include: { sender: true },
      take: 5,
      orderBy: { created_at: 'desc' }
    })
  ]);

  const stats = [
    { label: "Total Clients", value: clientCount, icon: Users, color: "text-emerald-600", bg: "bg-emerald-50" },
    { label: "Partner Orgs", value: orgCount, icon: Building2, color: "text-blue-600", bg: "bg-blue-50" },
    { label: "Tickets Issued", value: ticketCount, icon: Ticket, color: "text-amber-600", bg: "bg-amber-50" },
    { label: "Active Docs", value: docCount, icon: FileText, color: "text-purple-600", bg: "bg-purple-50" },
  ];

  return (
    <div className="space-y-10">
      {/* Welcome Section */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase">Ops Console</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Logistics & Client Management</p>
        </div>
        <div className="flex items-center gap-2 bg-white p-1 rounded-2xl border border-slate-200 shadow-sm">
           <button className="px-6 py-2.5 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-900/10">
             New Client
           </button>
           <button className="px-6 py-2.5 bg-white text-slate-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all border border-slate-100">
             Issue Ticket
           </button>
        </div>
      </div>

       {/* Stats Grid */}
       <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, i) => {
          const Icon = stat.icon;
          return (
            <div key={i} className="bg-white p-8 rounded-[2rem] border border-emerald-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group relative overflow-hidden">
               <div className={`absolute top-0 right-0 w-32 h-32 ${stat.bg} rounded-full -mr-16 -mt-16 opacity-40 group-hover:scale-110 transition-transform`} />
               
               <div className="relative z-10">
                 <div className={`w-12 h-12 ${stat.bg} ${stat.color} rounded-xl flex items-center justify-center mb-6`}>
                   <Icon size={24} />
                 </div>
                 <p className="text-slate-400 font-black text-[10px] uppercase tracking-[0.2em] mb-1">{stat.label}</p>
                 <div className="flex items-baseline gap-2">
                   <h3 className="text-3xl font-black text-slate-900">{stat.value}</h3>
                   <span className="text-emerald-500 text-[10px] font-bold flex items-center gap-0.5">
                     <TrendingUp size={10} /> +{Math.floor(Math.random() * 5 + 1)}%
                   </span>
                 </div>
               </div>
            </div>
          );
        })}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Incoming Dispatches */}
        <div className="lg:col-span-2 bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm">
           <div className="flex justify-between items-center mb-10">
              <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-3">
                 <Inbox size={24} className="text-blue-600" />
                 Incoming Dispatches
              </h2>
              <span className="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-blue-100 italic">
                 {dispatches.length} Pending
              </span>
           </div>
           
           <div className="space-y-4">
              {dispatches.length > 0 ? dispatches.map((dispatch) => (
                <div key={dispatch.id} className="p-6 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between group hover:bg-white hover:border-blue-200 hover:shadow-md transition-all">
                   <div className="flex items-center gap-4">
                      <div className="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-600 font-black text-xs uppercase shadow-sm">
                         {dispatch.sender.name[0]}
                      </div>
                      <div>
                         <p className="text-sm font-black text-slate-900 tracking-tight uppercase">{dispatch.subject}</p>
                         <p className="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">FROM: {dispatch.sender.name} &bull; {new Date(dispatch.created_at).toLocaleDateString()}</p>
                      </div>
                   </div>
                   <button className="flex items-center gap-2 px-6 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all group-hover:shadow-lg">
                      Open <ArrowUpRight size={14} />
                   </button>
                </div>
              )) : (
                <div className="py-20 text-center flex flex-col items-center gap-4 opacity-30">
                   <Send size={48} className="text-slate-200" />
                   <p className="text-slate-400 font-black text-sm uppercase tracking-widest italic text-center max-w-xs">The dispatch list is currently clear. No incoming directives at this time.</p>
                </div>
              )}
           </div>
        </div>

        {/* Quick Access Sidebar */}
        <div className="space-y-6">
           <div className="bg-slate-900 rounded-[2.5rem] p-10 text-white relative overflow-hidden">
              <div className="absolute top-0 right-0 w-48 h-48 bg-emerald-600/20 rounded-full -mr-24 -mt-24 blur-3xl opacity-50" />
              <h2 className="text-xl font-black uppercase tracking-tight mb-8 relative z-10">Client Portal</h2>
              <div className="space-y-3 relative z-10">
                 <button className="w-full p-5 bg-white/10 hover:bg-white/15 rounded-2xl border border-white/5 flex items-center justify-between transition-all">
                    <span className="text-xs font-black uppercase tracking-widest">Register Individual</span>
                    <Plus size={16} />
                 </button>
                 <button className="w-full p-5 bg-white/10 hover:bg-white/15 rounded-2xl border border-white/5 flex items-center justify-between transition-all">
                    <span className="text-xs font-black uppercase tracking-widest">Enroll Organization</span>
                    <Building2 size={16} />
                 </button>
              </div>
           </div>

           <div className="bg-emerald-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden">
              <h2 className="text-xl font-black uppercase tracking-tight mb-4">Passport Vault</h2>
              <p className="text-emerald-100 text-sm font-medium mb-8 leading-relaxed italic">
                Securely store and manage travel documents for international logistics.
              </p>
              <button className="w-full py-4 bg-white text-emerald-900 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl active:scale-[0.98] transition-all">
                Access Vault
              </button>
           </div>
        </div>
      </div>
    </div>
  );
}
