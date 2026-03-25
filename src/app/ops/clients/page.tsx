import { prisma } from "@/lib/prisma";
import Image from "next/image";
import Link from "next/link";
import { 
  Plus, 
  Search, 
  Filter, 
  MoreVertical, 
  Edit, 
  CheckCircle2, 
  User,
  Building2,
  Mail,
  History
} from "lucide-react";

export default async function ClientDatabasePage() {
  const clients = await prisma.client.findMany({
    orderBy: { created_at: 'desc' },
    include: { organization: true, registrar: true }
  });

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase">Client Database</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Unified records for individuals & groups</p>
        </div>
        <button className="flex items-center gap-3 px-8 py-4 bg-emerald-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-emerald-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
          <Plus size={16} />
          Register New Client
        </button>
      </div>

      {/* Filter Bar */}
      <div className="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div className="relative flex-1 w-full">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search by name, email, TIN or organization..." 
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-emerald-600 transition-all font-bold text-sm"
          />
        </div>
        <div className="flex gap-2 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-slate-50 text-slate-600 border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest">
            <Filter size={14} /> Filter
          </button>
        </div>
      </div>

      {/* Clients Table */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden whitespace-nowrap">
        <div className="overflow-x-auto custom-scrollbar">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50 border-b border-slate-100">
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Client Identity</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Classification</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Contact Point</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Operations Metadata</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {clients.length > 0 ? clients.map((client) => (
                <tr key={client.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-4">
                      <div className="w-12 h-12 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-black text-xs shadow-sm shadow-slate-900/5">
                         {client.name[0]}
                      </div>
                      <div>
                        <p className="font-black text-slate-900 text-sm tracking-tight">{client.name}</p>
                        <p className="text-[10px] font-bold text-emerald-600 tracking-widest uppercase italic">{client.tin ? `TIN: ${client.tin}` : 'Personal Account'}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-2">
                       {client.organization ? (
                         <div className="flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg border border-blue-100 italic">
                            <Building2 size={12} />
                            <span className="text-[9px] font-black uppercase tracking-widest truncate max-w-[120px]">{client.organization.name}</span>
                         </div>
                       ) : (
                         <div className="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">
                            <User size={12} />
                            <span className="text-[9px] font-black uppercase tracking-widest">INDIVIDUAL</span>
                         </div>
                       )}
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="space-y-1">
                       <div className="flex items-center gap-2 text-slate-600">
                          <Mail size={12} className="text-slate-400" />
                          <span className="text-xs font-bold truncate max-w-[150px]">{client.email || 'No email registered'}</span>
                       </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                     <div className="flex items-center gap-2 text-slate-400 italic">
                        <History size={14} />
                        <span className="text-[10px] font-bold uppercase tracking-widest">Registered {new Date(client.created_at).toLocaleDateString()}</span>
                     </div>
                  </td>
                  <td className="px-8 py-6 text-right">
                     <div className="flex items-center justify-end gap-2">
                        <button className="p-2.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition-all">
                          <Edit size={16} />
                        </button>
                        <button className="p-2.5 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                          <MoreVertical size={16} />
                        </button>
                     </div>
                  </td>
                </tr>
              )) : (
                <tr>
                   <td colSpan={5} className="py-20 text-center opacity-40">
                      <div className="flex flex-col items-center gap-4 border-2 border-dashed border-slate-100 rounded-3xl p-12 mx-12">
                         <Search size={48} className="text-slate-200" />
                         <p className="text-slate-400 font-black text-sm uppercase tracking-widest italic">The individual database is currently empty</p>
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
