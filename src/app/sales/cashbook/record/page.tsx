import { prisma } from "@/lib/prisma";
import { 
  ArrowLeft, 
  Plus, 
  ArrowUpRight, 
  ArrowDownRight, 
  Wallet, 
  Coins, 
  DollarSign,
  Briefcase,
  Info,
  Save,
  Plane,
  ShieldCheck
} from "lucide-react";
import Link from "next/link";
import { redirect } from "next/navigation";
import TransactionForm from "@/components/sales/TransactionForm";

export default async function RecordTransactionPage({
  searchParams,
}: {
  searchParams: Promise<{ type?: string; source?: string }>;
}) {
  const { type = "income", source = "main" } = await searchParams;

  const [categories, cashDay] = await Promise.all([
    prisma.cashCategory.findMany({
      where: { is_active: true, type }
    }),
    prisma.cashDay.findFirst({
      where: { status: 'open' },
      orderBy: { date: 'desc' },
    })
  ]);

  if (!cashDay) {
    redirect("/sales/cashbook?error=no_open_day");
  }

  return (
    <div className="max-w-4xl mx-auto space-y-8 pb-20 font-outfit">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href="/sales/cashbook" className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic font-black uppercase">Record Entry</h1>
          <p className="text-slate-400 font-bold text-[10px] tracking-[0.2em] uppercase mt-1 italic uppercase">Macro-Finance Ledger Commit</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Form Column */}
        <div className="lg:col-span-2">
           <TransactionForm 
            type={type} 
            source={source} 
            categories={categories} 
            cashDayId={cashDay.id} 
           />
        </div>

        {/* Sidebar Info */}
        <div className="space-y-8">
           <div className="bg-slate-900 p-8 rounded-[2.5rem] text-white italic shadow-xl relative overflow-hidden h-full">
              <div className="relative z-10">
                 <h3 className="text-[11px] font-black uppercase tracking-widest mb-10 border-b border-white/5 pb-6 flex items-center gap-3">
                    <Info size={18} className="text-blue-400" />
                    Protocol Help
                 </h3>
                 <div className="space-y-10">
                    <div>
                       <p className="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-3">Main vs Petty</p>
                       <p className="text-[10px] font-bold text-slate-400 uppercase leading-relaxed tracking-widest italic">
                          Main cash tracks operational liquidity. Petty cash is for daily office OPEX and must be replenished from main vault.
                       </p>
                    </div>
                    <div>
                       <p className="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-3">Ticket Inflow</p>
                       <p className="text-[10px] font-bold text-slate-400 uppercase leading-relaxed tracking-widest italic">
                          RC (Rate Collected) MUST equal TTC + Service Fee. Only the Service Fee component contributes to net yield.
                       </p>
                    </div>
                    <div className="pt-6 border-t border-white/5">
                       <p className="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 italic">Session Data</p>
                       <p className="text-[9px] font-bold text-white uppercase tracking-widest italic">Date: {new Date(cashDay.date).toLocaleDateString()}</p>
                       <p className="text-[9px] font-bold text-white uppercase tracking-widest italic mt-1">Status: Operational</p>
                    </div>
                 </div>
              </div>
              <div className="absolute -bottom-10 -right-10 p-8 opacity-[0.03]">
                 <DollarSign size={200} />
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}
