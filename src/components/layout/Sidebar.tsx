import React from "react";
import Link from "next/link";
import Image from "next/image";
import { auth, signOut } from "@/auth";
import { 
  BarChart3, 
  Users, 
  ShieldCheck, 
  Key, 
  Settings, 
  FileText, 
  ShieldAlert, 
  Briefcase, 
  HardDrive, 
  History, 
  LogOut,
  LayoutDashboard,
  RefreshCw,
  CloudRain,
  CreditCard,
  Plane,
  Building2,
  Lock
} from "lucide-react";

export default async function Sidebar() {
  const session = await auth();
  const role = (session?.user as any)?.role;

  const adminLinks = [
    { href: "/admin", label: "Admin Console", icon: LayoutDashboard },
    { href: "/admin/users", label: "Staff Directory", icon: Users },
    { href: "/admin/cloud-sync", label: "Cloud Command", icon: CloudRain },
    { href: "/admin/audit", label: "Activity Hub", icon: History },
    { href: "/admin/settings", label: "Portal Settings", icon: Settings },
  ];

  const salesLinks = [
    { href: "/sales", label: "Financial Control", icon: BarChart3 },
    { href: "/sales/cashbook", label: "Daily Cashbook", icon: CreditCard },
    { href: "/sales/invoices", label: "Invoice Tracker", icon: FileText },
    { href: "/sales/ebm", label: "EBM Registry", icon: ShieldCheck },
    { href: "/sales/tax", label: "Tax Hub", icon: Building2 },
    { href: "/sales/rra", label: "RRA Vault", icon: Lock },
    { href: "/sales/reports", label: "Financial Reports", icon: BarChart3 },
  ];

  const opsLinks = [
    { href: "/ops", label: "Ops Console", icon: LayoutDashboard },
    { href: "/ops/clients", label: "Client Database", icon: Users },
    { href: "/ops/passports", label: "Passport Vault", icon: HardDrive },
    { href: "/ops/tickets", label: "Ticket Management", icon: Plane },
  ];

  const mdLinks = [
    { href: "/md", label: "MD Command Center", icon: ShieldCheck },
    { href: "/md/employees", label: "Employee Base", icon: Users },
    { href: "/md/activity", label: "Activity Ledger", icon: History },
    { href: "/md/vault", label: "Corporate Vault", icon: Lock },
    { href: "/md/folders", label: "Folder Manager", icon: Briefcase },
  ];

  const currentLinks = 
    role === "admin" ? adminLinks :
    role === "sales" ? salesLinks :
    role === "operations" ? opsLinks :
    role === "md" ? mdLinks : [];


  return (
    <aside className="fixed lg:sticky top-0 left-0 z-40 w-[280px] bg-white h-screen border-r border-slate-200 p-6 flex flex-col shadow-sm overflow-hidden transition-transform duration-300 ease-in-out font-[sans-serif]">
      {/* Brand Section */}
      <div className="mb-10 flex flex-col items-center text-center">
        <div className="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center p-3 mb-4 border border-slate-100">
          <Image 
            src="/assets/brand/logo-removebg-preview.png" 
            alt="Logo" 
            width={64} 
            height={64} 
            className="object-contain"
          />
        </div>
        <h1 className="text-sm font-black text-slate-900 uppercase tracking-[0.2em]">STUNNING TRAVEL</h1>
        <p className="text-[10px] text-slate-400 font-bold tracking-[0.25em] uppercase mt-2 flex items-center gap-2">
          <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
          Management Console
        </p>
      </div>

      {/* Navigation Links */}
      <nav className="flex-1 space-y-1 overflow-y-auto pr-2 custom-scrollbar">
        {currentLinks.map((link) => {
          const Icon = link.icon;
          return (
            <Link
              key={link.href}
              href={link.href}
              className="group flex items-center space-x-3 py-3 px-4 rounded-xl text-slate-500 hover:bg-red-50 hover:text-red-700 transition-all font-bold group border-l-4 border-transparent hover:border-red-600"
            >
              <Icon size={18} className="group-hover:scale-110 transition-transform" />
              <span className="text-[11px] uppercase tracking-wider">{link.label}</span>
            </Link>
          );
        })}
      </nav>

      {/* Footer / Logout */}
      <div className="mt-auto pt-6 border-t border-slate-50">
        <form action={async () => {
          "use server";
          await signOut();
        }}>
          <button className="w-full flex items-center justify-center space-x-2 py-4 rounded-2xl bg-slate-50 text-slate-600 hover:bg-red-50 hover:text-red-600 transition-all font-black text-[10px] uppercase tracking-[0.2em] group border border-slate-100 shadow-sm">
            <LogOut size={16} className="group-hover:-translate-x-1 transition-transform" />
            <span>Secure Logout</span>
          </button>
        </form>
      </div>
    </aside>
  );
}
