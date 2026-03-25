import { prisma } from "@/lib/prisma";
import { 
  ShieldAlert, 
  CheckCircle2, 
  UploadCloud, 
  FileText, 
  Table as TableIcon,
  Search,
  Plus,
  ArrowRight,
  TrendingUp,
  CreditCard,
  Building2
} from "lucide-react";
import Link from "next/link";

export default async function TaxHubPage({
  searchParams,
}: {
  searchParams: Promise<{ type?: string; search?: string }>;
}) {
  const { type = "pension", search = "" } = await searchParams;

  const records = await prisma.taxCompliance.findMany({
    where: {
      type,
      declaration_number: { contains: search }
    },
    include: { uploader: true },
    orderBy: { created_at: 'desc' }
  });

  const stats = {
    total_declared: records.reduce((acc, curr) => acc + Number(curr.declared_amount), 0),
    total_payment: records.reduce((acc, curr) => acc + Number(curr.receipt_amount || 0), 0),
    pending_payments: records.filter(r => !r.receipt_amount).length,
  };

  const complianceRatio = stats.total_declared > 0 
    ? ((stats.total_payment / stats.total_declared) * 100).toFixed(1)
    : "0.0";

  const taxTypes = [
    { id: 'pension', name: 'Pension' },
    { id: 'maternity', name: 'Maternity' },
    { id: 'bhi', name: 'BHI (Health)' },
    { id: 'rra', name: 'RRA Corporate' },
  ];

  return (
    <div className="space-y-8">
      {/* Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Tax Compliance Hub</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic">Multi-Phase Fiscal Governance</p>
        </div>
        <div className="flex gap-3">
          <Link href={`/sales/tax/create?type=${type}`} className="px-6 py-3 bg-slate-900 text-white rounded-xl font-black uppercase tracking-widest text-[10px] flex items-center gap-2 hover:scale-[1.02] transition-all shadow-lg active:scale-[0.98]">
            <Plus size={14} /> New Declaration
          </Link>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div className="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group">
          <div className="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
            <FileText size={80} />
          </div>
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Total Declared</p>
          <p className="text-2xl font-black text-slate-900 italic">RWF {stats.total_declared.toLocaleString()}</p>
        </div>

        <div className="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group border-l-4 border-l-emerald-500">
           <div className="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-emerald-600">
            <CheckCircle2 size={80} />
          </div>
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Total Paid</p>
          <p className="text-2xl font-black text-emerald-600 italic">RWF {stats.total_payment.toLocaleString()}</p>
        </div>

        <div className="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm relative overflow-hidden group border-l-4 border-l-amber-500">
           <div className="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity text-amber-600">
            <ShieldAlert size={80} />
          </div>
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Pending Receipts</p>
          <p className="text-2xl font-black text-amber-600 italic">{stats.pending_payments} Records</p>
        </div>

        <div className="bg-slate-900 p-8 rounded-[2rem] shadow-xl relative overflow-hidden group">
           <div className="absolute top-0 right-0 p-4 opacity-[0.1] group-hover:opacity-[0.2] transition-opacity text-white">
            <TrendingUp size={80} />
          </div>
          <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 italic">Compliance Ratio</p>
          <p className="text-3xl font-black text-white italic">{complianceRatio}%</p>
        </div>
      </div>

      {/* Content Area */}
      <div className="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden min-h-[500px]">
        {/* Tabs */}
        <div className="flex border-b border-slate-100 overflow-x-auto">
          {taxTypes.map((t) => (
            <Link
              key={t.id}
              href={`/sales/tax?type=${t.id}`}
              className={`px-10 py-6 text-[10px] font-black uppercase tracking-[0.2em] transition-all border-b-2 whitespace-nowrap italic ${
                type === t.id 
                  ? "border-slate-900 text-slate-900 bg-slate-50/50" 
                  : "border-transparent text-slate-400 hover:text-slate-600"
              }`}
            >
              {t.name}
            </Link>
          ))}
        </div>

        <div className="p-10">
          <div className="flex justify-between items-center mb-8">
            <h2 className="text-xl font-black text-slate-900 uppercase tracking-tight flex items-center gap-3 italic">
              <TableIcon size={24} className="text-slate-400" />
              {type.toUpperCase()} LEDGER
            </h2>
            <div className="relative">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" size={16} />
              <input 
                type="text" 
                placeholder="Search Declaration #"
                className="pl-12 pr-6 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold focus:outline-none focus:border-slate-900 transition-all w-64 uppercase italic placeholder:italic"
              />
            </div>
          </div>

          <table className="w-full">
            <thead>
              <tr className="border-b border-slate-50">
                <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Phase 1: Declaration</th>
                <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Amount</th>
                <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Phase 2: Payment</th>
                <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Status</th>
                <th className="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest italic text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-50">
              {records.map((r) => (
                <tr key={r.id} className="group hover:bg-slate-50/50 transition-all">
                  <td className="py-8">
                    <div className="flex flex-col gap-1">
                      <span className="text-xs font-black text-slate-900 italic uppercase">#{r.declaration_number}</span>
                      <span className="text-[10px] font-bold text-slate-400 uppercase italic">Filed by {r.uploader.name}</span>
                    </div>
                  </td>
                  <td className="py-8 font-black text-xs italic">
                    RWF {Number(r.declared_amount).toLocaleString()}
                  </td>
                  <td className="py-8">
                    {r.receipt_amount ? (
                      <div className="flex flex-col gap-1">
                        <span className="text-xs font-black text-emerald-600 italic uppercase">PAID RWF {Number(r.receipt_amount).toLocaleString()}</span>
                        <span className="text-[10px] font-bold text-slate-400 uppercase italic">{r.payment_date ? new Date(r.payment_date).toLocaleDateString() : 'N/A'}</span>
                      </div>
                    ) : (
                      <span className="text-[10px] font-black text-slate-300 uppercase italic tracking-widest">Awaiting Proof</span>
                    )}
                  </td>
                  <td className="py-8">
                    <div className={`inline-flex items-center gap-2 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border italic ${
                      r.receipt_amount 
                        ? "bg-emerald-50 text-emerald-600 border-emerald-100" 
                        : "bg-amber-50 text-amber-600 border-amber-100 animate-pulse"
                    }`}>
                      {r.receipt_amount ? "Compliant" : "Outstanding"}
                    </div>
                  </td>
                  <td className="py-8 text-right">
                    {!r.receipt_amount ? (
                      <Link href={`/sales/tax/pay/${r.id}`} className="px-4 py-2 bg-slate-900 text-white rounded-lg text-[9px] font-black uppercase tracking-widest italic flex items-center gap-2 ml-auto w-fit hover:scale-105 transition-all">
                        Pay Now <ArrowRight size={12} />
                      </Link>
                    ) : (
                      <button className="text-[10px] font-black text-slate-400 uppercase italic hover:text-slate-900 transition-colors">
                        View Receipt
                      </button>
                    )}
                  </td>
                </tr>
              ))}
              {records.length === 0 && (
                <tr>
                   <td colSpan={5} className="py-32 text-center opacity-30 italic font-black uppercase tracking-[0.2em] text-sm">
                      No Records Stored
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
