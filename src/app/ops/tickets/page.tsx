import { prisma } from "@/lib/prisma";
import { 
  Ticket as TicketIcon, 
  Search, 
  Filter, 
  Calendar, 
  Plane,
  Download,
  Plus,
  ArrowRight,
  User,
  Hash
} from "lucide-react";
import Link from "next/link";

export default async function TicketManagementPage() {
  const tickets = await prisma.ticket.findMany({
    orderBy: { created_at: 'desc' },
  });

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase">Flight Tickets</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Operational flight log and PNR tracker</p>
        </div>
        <button className="flex items-center gap-3 px-8 py-4 bg-emerald-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-emerald-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
          <Plus size={16} /> Record New Ticket
        </button>
      </div>

       {/* Filter Bar */}
       <div className="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div className="relative flex-1 w-full">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search by PNR, Ticket No., or Client Name..." 
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-emerald-600 transition-all font-bold text-sm"
          />
        </div>
        <div className="flex gap-2 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-slate-50 text-slate-600 border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest">
            <Filter size={14} /> Refine Log
          </button>
        </div>
      </div>

      {/* Tickets Table */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden whitespace-nowrap">
        <div className="overflow-x-auto custom-scrollbar">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50 border-b border-slate-100">
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Client & Flight</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">PNR / Ticket No.</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Airline</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dates</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {tickets.length > 0 ? tickets.map((t) => (
                <tr key={t.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-4">
                      <div className="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-black text-xs">
                         <Plane size={20} />
                      </div>
                      <div>
                        <p className="font-black text-slate-900 text-sm tracking-tight">{t.client_names}</p>
                        <p className="text-[10px] font-bold text-slate-400 tracking-widest uppercase italic">Flight: {t.flight_number}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="space-y-1">
                       <div className="flex items-center gap-2">
                          <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">PNR:</span>
                          <span className="text-xs font-black text-slate-900 tracking-widest">{t.pnr}</span>
                       </div>
                       <div className="flex items-center gap-2">
                          <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest">TKT:</span>
                          <span className="text-xs font-bold text-slate-500">{t.ticket_number}</span>
                       </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <span className="text-xs font-black text-slate-900 uppercase tracking-tighter">{t.airline_name}</span>
                  </td>
                  <td className="px-8 py-6">
                    <div className="space-y-1">
                       <div className="flex items-center gap-2 text-slate-600">
                          <Calendar size={12} className="text-slate-400" />
                          <span className="text-xs font-bold">{new Date(t.departure_date).toLocaleDateString()}</span>
                       </div>
                       {t.return_date && (
                         <div className="flex items-center gap-2 text-slate-400 italic">
                            <span className="text-[9px] font-black uppercase tracking-widest">Return:</span>
                            <span className="text-xs font-bold">{new Date(t.return_date).toLocaleDateString()}</span>
                         </div>
                       )}
                    </div>
                  </td>
                  <td className="px-8 py-6 text-right">
                     <div className="flex items-center justify-end gap-2 text-slate-400">
                        <button className="p-2 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">
                          <Download size={18} />
                        </button>
                        <button className="p-2 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                          <Filter size={18} />
                        </button>
                     </div>
                  </td>
                </tr>
              )) : (
                <tr>
                   <td colSpan={5} className="py-20 text-center opacity-40">
                      <p className="text-slate-400 font-black text-sm uppercase tracking-widest italic border border-dashed border-slate-200 p-10 rounded-3xl mx-10">No flight tickets recorded in the system</p>
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
