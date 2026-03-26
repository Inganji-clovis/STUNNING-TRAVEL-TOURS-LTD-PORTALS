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
  Hash,
  Activity,
  AlertCircle,
  CheckCircle2,
  XCircle
} from "lucide-react";
import Link from "next/link";
import TicketForm from "@/components/ops/TicketForm";

export default async function TicketManagementPage() {
  const tickets = await prisma.ticket.findMany({
    orderBy: { created_at: 'desc' },
  });

  return (
    <div className="space-y-8 font-outfit uppercase font-black italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic font-black uppercase">Flight Tickets</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic uppercase font-black uppercase">Operational flight log and PNR tracker</p>
        </div>
        <TicketForm />
      </div>

       {/* Filter Bar */}
       <div className="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm flex flex-col md:flex-row gap-6 items-center italic">
        <div className="relative flex-1 w-full">
          <Search className="absolute left-6 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search by PNR, Ticket No., or Client Name..." 
            className="w-full pl-16 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest focus:outline-none focus:border-emerald-500 transition-all shadow-sm"
          />
        </div>
        <div className="flex gap-4 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center gap-3 px-10 py-4 bg-slate-50 text-slate-500 border border-slate-100 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-100 transition-all">
            <Filter size={16} /> Filters
          </button>
        </div>
      </div>

      {/* Tickets Table */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden whitespace-nowrap italic">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-slate-50/50 border-b border-slate-100">
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Client & Flight</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">PNR / Ticket No.</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Segment</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Status</th>
                <th className="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50 font-black">
              {tickets.length > 0 ? tickets.map((t) => (
                <tr key={t.id} className="hover:bg-slate-50/50 transition-all group">
                  <td className="px-10 py-8">
                    <div className="flex items-center gap-4">
                      <div className="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm group-hover:scale-110 transition-transform">
                         <Plane size={24} />
                      </div>
                      <div>
                        <p className="font-black text-slate-900 text-sm tracking-tight italic leading-none">{t.client_names}</p>
                        <p className="text-[9px] font-bold text-slate-400 tracking-[0.2em] uppercase mt-2 italic flex items-center gap-2">
                           FLT: {t.flight_number} <ArrowRight size={10} /> {t.airline_name}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td className="px-10 py-8">
                    <div className="space-y-2">
                       <div className="flex items-center gap-2 bg-slate-900 text-white px-3 py-1 rounded-lg self-start inline-flex shadow-lg">
                          <Hash size={10} className="text-slate-500" />
                          <span className="text-[10px] font-black tracking-[0.2em]">{t.pnr}</span>
                       </div>
                       <p className="text-[10px] font-bold text-slate-400 tracking-widest italic ml-1">TKT: {t.ticket_number}</p>
                    </div>
                  </td>
                  <td className="px-10 py-8">
                    <div className="space-y-2">
                       <div className="flex items-center gap-2 text-slate-700">
                          <Calendar size={12} className="text-emerald-500" />
                          <span className="text-[10px] font-black italic">{new Date(t.departure_date).toLocaleDateString()}</span>
                       </div>
                       {t.return_date && (
                         <div className="flex items-center gap-2 text-slate-400">
                            <span className="text-[8px] font-black uppercase tracking-widest">Return:</span>
                            <span className="text-[9px] font-bold">{new Date(t.return_date).toLocaleDateString()}</span>
                         </div>
                       )}
                    </div>
                  </td>
                  <td className="px-10 py-8">
                    <span className={`px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest border italic flex items-center gap-2 w-fit ${
                      t.status === 'issued' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                      t.status === 'cancelled' ? 'bg-red-50 text-red-600 border-red-100' :
                      'bg-slate-50 text-slate-400 border-slate-100'
                    }`}>
                      {t.status === 'issued' ? <CheckCircle2 size={12} /> : t.status === 'cancelled' ? <XCircle size={12} /> : <AlertCircle size={12} />}
                      {t.status}
                    </span>
                  </td>
                  <td className="px-10 py-8 text-right">
                     <div className="flex items-center justify-end gap-3 text-slate-400">
                        <Link 
                          href={`/api/vault/ticket/${t.id}`}
                          target="_blank"
                          className="p-3 bg-slate-50 border border-slate-100 rounded-2xl hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-100 transition-all shadow-sm"
                        >
                          <Download size={20} />
                        </Link>
                        <button className="p-3 bg-slate-50 border border-slate-100 rounded-2xl hover:text-slate-900 hover:bg-slate-100 transition-all shadow-sm">
                          <Activity size={20} />
                        </button>
                     </div>
                  </td>
                </tr>
              )) : (
                <tr>
                   <td colSpan={5} className="py-40 text-center opacity-30">
                      <div className="flex flex-col items-center gap-6">
                        <div className="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                           <TicketIcon size={48} />
                        </div>
                        <p className="text-slate-400 font-black text-md uppercase tracking-[0.3em] italic">Operational flight ledger depleted</p>
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
