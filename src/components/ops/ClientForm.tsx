"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { toast } from "sonner";
import { Plus, X, User, Building2, Mail, Phone, Hash, Save, ShieldCheck } from "lucide-react";
import { upsertClient, upsertOrganization } from "@/lib/client-actions";

interface ClientFormProps {
  initialData?: any;
  organizations: { id: number; name: string }[];
}

export default function ClientForm({ initialData, organizations }: ClientFormProps) {
  const router = useRouter();
  const [isOpen, setIsOpen] = useState(false);
  const [isOrgModalOpen, setIsOrgModalOpen] = useState(false);
  const [isPending, setIsPending] = useState(false);

  async function handleSubmit(formData: FormData) {
    setIsPending(true);
    const result = await upsertClient(formData);
    setIsPending(false);

    if (result.success) {
      toast.success(result.message);
      setIsOpen(false);
      router.refresh();
    } else {
      toast.error(result.message);
    }
  }

  async function handleOrgSubmit(formData: FormData) {
    setIsPending(true);
    const result = await upsertOrganization(formData);
    setIsPending(false);

    if (result.success) {
      toast.success("Organization added to registry");
      setIsOrgModalOpen(false);
    } else {
      toast.error(result.message);
    }
  }

  return (
    <>
      <button 
        onClick={() => setIsOpen(true)}
        className="flex items-center gap-3 px-8 py-4 bg-emerald-600 text-white text-xs font-black uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-emerald-900/20 hover:scale-[1.02] active:scale-[0.98] transition-all"
      >
        <Plus size={16} /> {initialData ? "Edit Profile" : "Register New Client"}
      </button>

      {isOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 font-outfit uppercase font-black">
          <div className="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-emerald-50/50">
              <div className="flex items-center gap-4 text-emerald-600">
                <div className="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm border border-emerald-100">
                  <User size={24} />
                </div>
                <div>
                  <h2 className="text-2xl font-black uppercase tracking-tight italic">Client Information</h2>
                  <p className="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mt-1">Operational CRM Archive Protocol</p>
                </div>
              </div>
              <button onClick={() => setIsOpen(false)} className="p-2 hover:bg-emerald-100 rounded-full transition-colors">
                <X size={20} className="text-emerald-300" />
              </button>
            </div>

            <form action={handleSubmit} className="p-8 space-y-8 overflow-y-auto italic">
              <input type="hidden" name="id" value={initialData?.id} />
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Legal Name</label>
                  <div className="relative">
                    <User className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="name" defaultValue={initialData?.name} required placeholder="Ex: Jean Luc Piccard" className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs focus:ring-2 focus:ring-emerald-500/20 transition-all" />
                  </div>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Corporate Link</label>
                  <div className="flex gap-2">
                    <select name="organization_id" defaultValue={initialData?.organization_id} className="flex-1 px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs">
                      <option value="">Individual (No Org)</option>
                      {organizations.map(org => <option key={org.id} value={org.id}>{org.name}</option>)}
                    </select>
                    <button 
                      type="button"
                      onClick={() => setIsOrgModalOpen(true)}
                      className="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100 flex items-center justify-center hover:bg-emerald-100 transition-colors"
                    >
                      <Building2 size={20} />
                    </button>
                  </div>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
                  <div className="relative">
                    <Mail className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="email" type="email" defaultValue={initialData?.email} placeholder="client@corp.com" className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                  </div>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Protocol</label>
                  <div className="relative">
                    <Phone className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="phone" defaultValue={initialData?.phone} placeholder="+250..." className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                  </div>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">TIN (Optional)</label>
                  <div className="relative">
                    <Hash className="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                    <input name="tin" defaultValue={initialData?.tin} placeholder="Tax ID" className="w-full pl-14 pr-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                  </div>
                </div>

                <div className="space-y-3">
                  <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Membership Tier</label>
                  <select name="membership_type" defaultValue={initialData?.membership_type || "standard"} className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs">
                    <option value="standard">Standard</option>
                    <option value="gold">Gold Elite</option>
                    <option value="platinum">Platinum Strategic</option>
                    <option value="corporate">Corporate Group</option>
                  </select>
                </div>
              </div>

              <div className="pt-8 border-t border-slate-100 flex items-center justify-between">
                 <div className="flex items-center gap-2">
                    <ShieldCheck size={16} className="text-emerald-500" />
                    <span className="text-[9px] font-black uppercase text-slate-400 tracking-widest italic">Identity Records Encrypted</span>
                 </div>
                 <button 
                  disabled={isPending}
                  className="px-12 py-5 bg-slate-900 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl active:scale-95 disabled:opacity-50"
                 >
                    {isPending ? "Validating..." : <><Save size={18} /> Commit Registry</>}
                 </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Organization Quick-Add Modal */}
      {isOrgModalOpen && (
        <div className="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 font-outfit uppercase font-black">
          <div className="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl border border-slate-200 overflow-hidden flex flex-col">
            <div className="p-8 border-b border-slate-100 flex justify-between items-center bg-blue-50/50">
              <div className="flex items-center gap-4 text-blue-600">
                <Building2 size={24} />
                <h2 className="text-xl font-black uppercase tracking-tight italic">Quick-Add Org</h2>
              </div>
              <button onClick={() => setIsOrgModalOpen(false)} className="p-2 hover:bg-blue-100 rounded-full transition-colors">
                <X size={20} className="text-blue-300" />
              </button>
            </div>
            <form action={handleOrgSubmit} className="p-8 space-y-6 italic">
              <div className="space-y-4">
                <div>
                  <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Entity Name</label>
                  <input name="name" required placeholder="Organization Name" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>
                <div>
                  <label className="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Business TIN</label>
                  <input name="tin" placeholder="Tax ID" className="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl font-black text-slate-900 text-xs" />
                </div>
              </div>
              <button disabled={isPending} className="w-full py-5 bg-blue-600 text-white rounded-[1.5rem] font-black uppercase tracking-[0.2em] text-[10px]">
                Register Organization
              </button>
            </form>
          </div>
        </div>
      )}
    </>
  );
}
