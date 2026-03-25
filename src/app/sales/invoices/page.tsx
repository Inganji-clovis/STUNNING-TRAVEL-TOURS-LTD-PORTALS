import { prisma } from "@/lib/prisma";
import { 
  Plus, 
  Search, 
  Filter, 
  FileText, 
  CheckCircle2, 
  Clock, 
  AlertCircle,
  MoreVertical,
  Download,
  Building2,
  User
} from "lucide-react";
import Link from "next/link";

export default async function InvoiceTrackerPage() {
  const invoices = await prisma.billingDocument.findMany({
    orderBy: { created_at: 'desc' },
    include: { client: true, organization: true }
  });

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Invoice Tracker</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1">Billing Lifecycle Management</p>
        </div>
        <div className="flex gap-2">
          <Link href="/sales/invoices/create" className="flex items-center gap-3 px-8 py-4 bg-slate-900 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all">
            <Plus size={16} /> New Invoice
          </Link>
        </div>
      </div>

       {/* Filters */}
       <div className="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div className="relative flex-1 w-full">
          <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
          <input 
            type="text" 
            placeholder="Search documents, clients or organizations..." 
            className="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:outline-none focus:border-blue-600 transition-all font-bold text-sm"
          />
        </div>
        <div className="flex gap-2 w-full md:w-auto">
          <button className="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-3 bg-slate-50 text-slate-600 border border-slate-200 rounded-2xl font-black text-[10px] uppercase tracking-widest">
            <Filter size={14} /> Refine View
          </button>
        </div>
      </div>

      {/* Invoice Table */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden whitespace-nowrap">
        <div className="overflow-x-auto custom-scrollbar">
          <table className="w-full text-left border-collapse">
            <thead>
              <tr className="bg-slate-50 border-b border-slate-100">
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Document</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Client / Org</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                <th className="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {invoices.length > 0 ? invoices.map((inv) => (
                <tr key={inv.id} className="hover:bg-slate-50/50 transition-colors group">
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-4">
                      <div className="w-10 h-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 font-black text-xs">
                         <FileText size={20} />
                      </div>
                      <div>
                        <p className="font-black text-slate-900 text-sm tracking-tight">{inv.document_number}</p>
                        <p className="text-[10px] font-bold text-slate-400 tracking-widest uppercase italic">{new Date(inv.created_at).toLocaleDateString()}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <div className="flex items-center gap-2 max-w-[200px] overflow-hidden">
                       {inv.organization ? (
                         <div className="flex items-center gap-2">
                            <Building2 size={14} className="text-slate-400" />
                            <span className="text-xs font-bold text-slate-900 truncate uppercase tracking-tighter">{inv.organization.name}</span>
                         </div>
                       ) : (
                         <div className="flex items-center gap-2">
                            <User size={14} className="text-slate-400" />
                            <span className="text-xs font-bold text-slate-900 truncate uppercase tracking-tighter">{inv.client?.name || 'Walk-in Client'}</span>
                         </div>
                       )}
                    </div>
                  </td>
                  <td className="px-8 py-6">
                    <span className="font-black text-slate-900 text-sm italic">{inv.currency} {Number(inv.amount).toLocaleString()}</span>
                  </td>
                  <td className="px-8 py-6">
                    <span className={`px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest border flex items-center w-fit gap-1.5 ${
                        inv.status === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' :
                        inv.status === 'cancelled' ? 'bg-red-50 text-red-600 border-red-100' :
                        'bg-amber-50 text-amber-600 border-amber-100 italic'
                      }`}>
                        {inv.status === 'paid' ? <CheckCircle2 size={12} /> : 
                         inv.status === 'cancelled' ? <AlertCircle size={12} /> : 
                         <Clock size={12} />}
                        {inv.status}
                    </span>
                  </td>
                  <td className="px-8 py-6 text-right">
                     <div className="flex items-center justify-end gap-2 text-slate-400">
                        <button className="p-2 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                          <Download size={18} />
                        </button>
                        <button className="p-2 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                          <MoreVertical size={18} />
                        </button>
                     </div>
                  </td>
                </tr>
              )) : (
                <tr>
                   <td colSpan={5} className="py-20 text-center opacity-40">
                      <p className="text-slate-400 font-black text-sm uppercase tracking-widest italic border border-dashed border-slate-200 p-10 rounded-3xl mx-10">No billing documents recorded in the system</p>
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
