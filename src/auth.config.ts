import type { NextAuthConfig } from "next-auth";
import Credentials from "next-auth/providers/credentials";
import { prisma } from "@/lib/prisma";
import bcrypt from "bcryptjs";

export const authConfig = {
  pages: {
    signIn: "/login",
  },
  callbacks: {
    authorized({ auth, request: { nextUrl } }) {
      const isLoggedIn = !!auth?.user;
      const isLoginPage = nextUrl.pathname.startsWith("/login");
      const isPublicAsset = nextUrl.pathname.startsWith("/assets") || nextUrl.pathname.search(/\.(png|jpg|jpeg|gif|svg)$/) !== -1;

      if (isLoginPage) {
        if (isLoggedIn) return Response.redirect(new URL("/", nextUrl));
        return true;
      }

      if (isPublicAsset) return true;

      return isLoggedIn;
    },
    async jwt({ token, user, trigger, session }) {
      if (user) {
        token.role = (user as any).role;
        token.id = user.id;
        token.username = (user as any).username;
      }
      return token;
    },
    async session({ session, token }) {
      if (token) {
        session.user.id = token.id as string;
        (session.user as any).role = token.role as string;
        (session.user as any).username = token.username as string;
      }
      return session;
    },
  },
  providers: [
    Credentials({
      async authorize(credentials) {
        const { username, password } = credentials as any;

        const user = await prisma.user.findFirst({
          where: {
            OR: [
              { username: username },
              { email: username }
            ]
          },
        });

        if (!user || !user.password) return null;

        const passwordsMatch = await bcrypt.compare(password, user.password);

        if (passwordsMatch) {
          return {
            id: user.id.toString(),
            name: user.name,
            email: user.email,
            role: user.role,
            username: user.username,
          };
        }

        return null;
      },
    }),
  ],
} satisfies NextAuthConfig;
