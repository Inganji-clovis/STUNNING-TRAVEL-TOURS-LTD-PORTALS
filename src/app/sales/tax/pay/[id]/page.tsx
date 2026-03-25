import { prisma } from "@/lib/prisma";
import { 
  Building2, 
  CreditCard, 
  ArrowLeft, 
  ShieldCheck, 
  ShieldAlert,
  Save,
  CheckCircle2
} from "lucide-react";
import Link from "next/link";
import { payTax } from "@/lib/tax-actions";
import { notFound } from "next/navigation";

export default async function PayTaxPage({
  params,
}: {
  params: Promise<{ id: string }>;
}) {
  const { id } = await params;
  const tax = await prisma.taxCompliance.findUnique({
    where: { id: parseInt(id) },
    include: { uploader: true }
  });

  if (!tax) notFound();

  return (
    <div className="max-w-4xl mx-auto space-y-8">
      {/* Header */}
      <div className="flex items-center gap-6">
        <Link href={`/sales/tax?type=${tax.type}`} className="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all shadow-sm">
           <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Finalize Compliance</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-1 italic">Phase 2: Payment Receipt & Reconciliation</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2">
           <form action={payTax} className="bg-white rounded-[2.5rem] p-10 border border-slate-200 shadow-sm space-y-8 italic">
              <input type="hidden" name="id" value={id} />
              
              <div className="p-8 bg-slate-900 rounded-3xl text-white relative overflow-hidden">
                 <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 italic">Linked Declaration</p>
                 <div className="flex justify-between items-end">
                    <div>
                       <h3 className="text-xl font-black tracking-tight">#{tax.declaration_number}</h3>
                       <p className="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-1 italic">{tax.type.toUpperCase()} COMPLIANCE</p>
                    </div>
                    <div className="text-right">
                       <p className="text-2xl font-black italic">RWF {Number(tax.declared_amount).toLocaleString()}</p>
                       <p className="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pending Amount</p>
                    </div>
                 </div>
                 <div className="absolute top-0 right-0 p-4 opacity-5">
                    <CheckCircle2 size={100} />
                 </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Receipt Amount (RWF)</label>
                    <div className="relative">
                       <CreditCard className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="receipt_amount"
                         type="number" 
                         required
                         defaultValue={Number(tax.declared_amount)}
                         placeholder="0.00"
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all italic"
                       />
                    </div>
                 </div>

                 <div className="space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Date</label>
                    <div className="relative">
                       <ShieldCheck className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                       <input 
                         name="payment_date"
                         type="date" 
                         required
                         defaultValue={new Date().toISOString().split('T')[0]}
                         className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic"
                       />
                    </div>
                 </div>

                 <div className="col-span-full space-y-3">
                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Proof (Receipt Photo/PDF)</label>
                    <div className="p-10 border-2 border-dashed border-slate-200 rounded-3xl bg-slate-50 flex flex-col items-center justify-center gap-4 group hover:border-slate-900 transition-all cursor-pointer relative overflow-hidden">
                       <input type="file" name="receipt_file" required className="absolute inset-0 opacity-0 cursor-pointer" />
                       <div className="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-slate-300 group-hover:text-slate-900 transition-all border border-slate-100">
                          <CreditCard size={24} />
                       </div>
                       <div className="text-center">
                          <p className="text-[10px] font-black text-slate-900 uppercase tracking-widest italic leading-relaxed">Upload payment bank receipt</p>
                          <p className="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-1">Proof of actual funds transfer</p>
                       </div>
                    </div>
                 </div>
              </div>

              <div className="pt-6 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldAlert size={14} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic leading-none">Reconciling ledger upon submission</span>
                 </div>
                 <button className="px-10 py-4 bg-emerald-600 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-xl active:scale-95">
                    <CheckCircle2 size={16} /> Mark as Compliant
                 </button>
              </div>
           </form>
        </div>

        <div className="space-y-8">
           <div className="bg-white p-8 rounded-[2rem] border border-slate-200 italic shadow-sm">
              <h3 className="text-[10px] font-black text-slate-900 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                 <ShieldCheck size={14} className="text-emerald-500" />
                 RECONCILIATION PROTOCOL
              </h3>
              <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed mb-6">
                 Phase 2 requires the actual bank receipt. The amount paid must match the declaration exactly to maintain audit integrity.
              </p>
              <div className="space-y-3">
                 <div className="flex items-center justify-between text-[10px] font-black uppercase italic">
                    <span className="text-slate-400">Phase 1</span>
                    <span className="text-blue-500">Declared ✔</span>
                 </div>
                 <div className="flex items-center justify-between text-[10px] font-black uppercase italic">
                    <span className="text-slate-900">Phase 2</span>
                    <span className="text-emerald-500 underline decoration-2 underline-offset-4">Pending Proof</span>
                 </div>
              </div>
           </div>
        </div>
      </div>
    </div>
  );
}
