import { prisma } from "@/lib/prisma";
import { 
  Folder, 
  Search, 
  Plus, 
  ArrowRight, 
  User, 
  Building2, 
  FileText,
  Calendar,
  Filter,
  MoreVertical,
  ChevronRight,
  ShieldAlert,
  Archive
} from "lucide-react";
import Link from "next/link";
import { createCorporateFolder } from "@/lib/vault-actions";

export default async function MdFoldersPage({
  searchParams,
}: {
  searchParams: Promise<{ type?: string; search?: string }>;
}) {
  const { type = "client", search = "" } = await searchParams;

  const [folders, clients, organizations] = await Promise.all([
    prisma.corporateFolder.findMany({
      where: {
        AND: [
          { type },
          search ? { name: { contains: search } } : {}
        ]
      },
      include: {
        client: true,
        organization: true,
        creator: true,
        _count: { select: { files: true } }
      },
      orderBy: { created_at: "desc" }
    }),
    prisma.client.findMany({ orderBy: { name: "asc" } }),
    prisma.organization.findMany({ orderBy: { name: "asc" } })
  ]);

  const tabs = [
    { id: "client", name: "Client Dossiers", icon: User },
    { id: "contract", name: "Legal Contracts", icon: FileText },
    { id: "letter", name: "Official Letters", icon: Archive },
  ];

  return (
    <div className="space-y-10 pb-20 italic">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-10 rounded-[2.5rem] border border-slate-200 shadow-sm">
        <div>
          <h1 className="text-4xl font-black text-slate-900 tracking-tight uppercase italic">Folder Manager</h1>
          <p className="text-slate-400 font-bold text-sm tracking-widest uppercase mt-2 italic capitalize italic">MD-Exclusive Categorical Filing System</p>
        </div>
        
        <button 
          onClick={() => document.getElementById('new-folder-modal')?.classList.remove('hidden')}
          className="px-10 py-5 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-xl active:scale-95"
        >
          <Plus size={20} strokeWidth={3} /> Create New Folder
        </button>
      </div>

      {/* Tabs */}
      <div className="flex gap-4 p-2 bg-slate-100 rounded-[2rem] w-fit">
        {tabs.map((tab) => (
          <Link 
            key={tab.id}
            href={`/md/folders?type=${tab.id}`}
            className={`px-8 py-4 rounded-[1.5rem] font-black uppercase tracking-widest text-[10px] flex items-center gap-3 transition-all ${type === tab.id ? 'bg-white text-slate-900 shadow-md' : 'text-slate-400 hover:text-slate-600'}`}
          >
            <tab.icon size={16} /> {tab.name}
          </Link>
        ))}
      </div>

      {/* Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {folders.map((folder) => {
          const Icon = tabs.find(t => t.id === folder.type)?.icon || Folder;
          return (
            <Link 
              key={folder.id}
              href={`/md/folders/${folder.id}`}
              className="bg-white p-8 rounded-[2.5rem] border border-slate-200 hover:border-slate-900 hover:shadow-2xl transition-all group relative overflow-hidden"
            >
              <div className="flex items-center justify-between mb-6 relative z-10">
                <div className="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                  <Icon size={24} />
                </div>
                <div className="text-right">
                  <span className="text-[10px] font-black text-slate-400 uppercase tracking-widest block">{folder.year}</span>
                  <span className="text-[9px] font-black text-emerald-500 uppercase tracking-widest flex items-center gap-1 justify-end mt-1">
                    <ShieldAlert size={10} /> Secure Dossier
                  </span>
                </div>
              </div>
              
              <h3 className="text-xl font-black text-slate-900 uppercase tracking-tight mb-2 group-hover:translate-x-1 transition-transform">{folder.name}</h3>
              <p className="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic mb-6">
                {folder.client?.name || folder.organization?.name || "General Corporate Filing"}
              </p>

              <div className="pt-6 border-t border-slate-50 flex items-center justify-between relative z-10">
                <div className="flex items-center gap-2">
                  <span className="text-[10px] font-black text-slate-900 px-3 py-1 bg-slate-50 rounded-lg">{folder._count.files} Files</span>
                </div>
                <div className="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-900 group-hover:text-white transition-all">
                  <ChevronRight size={16} />
                </div>
              </div>

              {/* Background Decoration */}
              <div className="absolute right-[-10%] bottom-[-10%] opacity-[0.02] group-hover:opacity-[0.05] transition-opacity rotate-12">
                  <Folder size={150} />
              </div>
            </Link>
          );
        })}
        
        {folders.length === 0 && (
          <div className="col-span-full py-20 text-center bg-slate-50/50 rounded-[3rem] border border-dashed border-slate-200 space-y-4">
              <Folder size={40} className="mx-auto text-slate-200" />
              <p className="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] italic">No archived folders in this category</p>
          </div>
        )}
      </div>

      {/* New Folder Modal */}
      <div id="new-folder-modal" className="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-50 hidden flex items-center justify-center p-6 italic">
          <div className="bg-white rounded-[3rem] w-full max-w-lg overflow-hidden shadow-2xl relative">
              <div className="p-10 border-b border-slate-100 flex items-center justify-between">
                  <div>
                      <h2 className="text-2xl font-black text-slate-900 uppercase">New Dossier</h2>
                      <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1 italic">Establish categorical filing directory</p>
                  </div>
                  <button onClick={() => document.getElementById('new-folder-modal')?.classList.add('hidden')} className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-all">
                      <Plus size={20} className="rotate-45" />
                  </button>
              </div>
              <form action={createCorporateFolder} className="p-10 space-y-8">
                  <div className="space-y-3">
                      <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Folder Reference Name</label>
                      <input name="name" required placeholder="e.g. 2024 Legal Contracts - Alpha Group" type="text" className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic text-xs" />
                  </div>
                  
                  <div className="grid grid-cols-1 gap-8">
                      <div className="space-y-3">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Classification Type</label>
                          <select name="type" required className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic text-xs">
                              {tabs.map(t => <option key={t.id} value={t.id}>{t.name}</option>)}
                          </select>
                      </div>
                      
                      <div className="space-y-3">
                          <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Client Association (Optional)</label>
                          <select name="client_id" className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl font-black text-slate-900 focus:outline-none focus:border-slate-900 transition-all uppercase italic text-xs">
                              <option value="">N/A - General Corporate</option>
                              {clients.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                          </select>
                      </div>
                  </div>

                  <div className="pt-4 flex items-center justify-end">
                      <button className="px-12 py-5 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] flex items-center gap-3 hover:scale-105 transition-all shadow-2xl">
                        Identify Folder <ArrowRight size={16} />
                      </button>
                  </div>
              </form>
          </div>
      </div>
    </div>
  );
}
