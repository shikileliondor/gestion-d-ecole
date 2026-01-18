<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Bonjour Jonathan,') }}</p>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Earning Report') }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 3.529 9.712l3.63 3.63a.75.75 0 1 0 1.061-1.06l-3.63-3.631A5.5 5.5 0 0 0 9 3.5Zm-4 5.5a4 4 0 1 1 8 0 4 4 0 0 1-8 0Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <input
                        type="text"
                        placeholder="Search"
                        class="w-48 rounded-full border border-gray-200 bg-white py-2 pl-9 pr-4 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                    />
                </div>
                <button class="flex h-9 w-9 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 shadow-sm hover:text-indigo-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M10 2a6 6 0 0 0-6 6v2.232l-.893 2.679A1 1 0 0 0 4.054 14H6.1a3.9 3.9 0 0 0 7.8 0h2.046a1 1 0 0 0 .947-1.089L16 10.232V8a6 6 0 0 0-6-6Z" />
                    </svg>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[240px_1fr]">
                <aside class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500 text-white">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M4 3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V3Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-widest text-gray-400">GuiltyS</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Main Menu</p>
                        </div>
                    </div>
                    <nav class="mt-6 space-y-2 text-sm">
                        <a class="flex items-center gap-3 rounded-xl bg-indigo-50 px-3 py-2 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300" href="#">
                            <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                            Dashboard
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-3 py-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700/40" href="#">
                            Appearance
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-3 py-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700/40" href="#">
                            Notifications
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-3 py-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700/40" href="#">
                            Traffic Revenue
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-3 py-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700/40" href="#">
                            Data Science
                        </a>
                        <a class="flex items-center gap-3 rounded-xl px-3 py-2 text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700/40" href="#">
                            App Admin
                        </a>
                    </nav>
                    <div class="mt-8 rounded-2xl border border-dashed border-gray-200 p-4 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        Help Center
                    </div>
                </aside>

                <section class="space-y-6">
                    <div class="grid gap-6 lg:grid-cols-3">
                        <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800 lg:col-span-2">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Earnings Report</h3>
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-500 dark:bg-gray-700 dark:text-gray-300">Last 30 days</span>
                            </div>
                            <div class="mt-4 grid gap-4 md:grid-cols-[180px_1fr]">
                                <div class="rounded-xl bg-indigo-50 p-4 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">
                                    <p class="text-xs uppercase tracking-widest">Monthly</p>
                                    <p class="mt-2 text-2xl font-semibold">$345,77</p>
                                    <p class="text-xs text-indigo-500">+12% from last month</p>
                                    <button class="mt-4 rounded-full bg-indigo-600 px-4 py-2 text-xs font-semibold text-white">View Report</button>
                                </div>
                                <div class="rounded-xl bg-indigo-600 p-4 text-white">
                                    <div class="flex items-center justify-between text-xs text-indigo-100">
                                        <span>Oct</span>
                                        <span>Apr</span>
                                    </div>
                                    <div class="mt-4 flex h-36 items-end gap-2">
                                        <div class="h-12 flex-1 rounded-full bg-indigo-300/70"></div>
                                        <div class="h-20 flex-1 rounded-full bg-white/70"></div>
                                        <div class="h-16 flex-1 rounded-full bg-indigo-200/70"></div>
                                        <div class="h-24 flex-1 rounded-full bg-white"></div>
                                        <div class="h-14 flex-1 rounded-full bg-indigo-200/70"></div>
                                        <div class="h-28 flex-1 rounded-full bg-white"></div>
                                        <div class="h-18 flex-1 rounded-full bg-indigo-200/70"></div>
                                        <div class="h-32 flex-1 rounded-full bg-white"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 grid gap-4 md:grid-cols-3">
                                <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700">
                                    <p class="text-xs text-gray-400">Total Earnings</p>
                                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">$234,65</p>
                                </div>
                                <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700">
                                    <p class="text-xs text-gray-400">Item Earnings</p>
                                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">$253,74</p>
                                </div>
                                <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700">
                                    <p class="text-xs text-gray-400">Tax Earnings</p>
                                    <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">$21,23</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Earnings By Item</h3>
                                <span class="text-xs text-gray-400">Yearly</span>
                            </div>
                            <div class="mt-6 flex items-center justify-center">
                                <div class="relative h-36 w-36">
                                    <div class="absolute inset-0 rounded-full border-[10px] border-indigo-500/30"></div>
                                    <div class="absolute inset-0 rounded-full border-[10px] border-t-indigo-500 border-r-pink-400 border-b-teal-400 border-l-yellow-300"></div>
                                    <div class="absolute inset-0 flex items-center justify-center text-center">
                                        <div>
                                            <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">$13,6k</p>
                                            <p class="text-xs text-gray-400">Total</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 space-y-3 text-xs text-gray-500 dark:text-gray-300">
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-indigo-500"></span>Product</span>
                                    <span>48%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-pink-400"></span>Services</span>
                                    <span>24%</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-teal-400"></span>Support</span>
                                    <span>16%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-3">
                        <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Latest User</h3>
                            <div class="mt-4 space-y-4 text-xs text-gray-500 dark:text-gray-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">22k</p>
                                        <p>New Users</p>
                                    </div>
                                    <div class="flex h-10 w-32 items-end gap-1">
                                        <div class="h-4 w-4 rounded bg-indigo-200"></div>
                                        <div class="h-7 w-4 rounded bg-indigo-400"></div>
                                        <div class="h-5 w-4 rounded bg-indigo-300"></div>
                                        <div class="h-9 w-4 rounded bg-indigo-500"></div>
                                        <div class="h-6 w-4 rounded bg-indigo-200"></div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-100">12k</p>
                                        <p>New Users</p>
                                    </div>
                                    <div class="flex h-10 w-32 items-end gap-1">
                                        <div class="h-4 w-4 rounded bg-pink-200"></div>
                                        <div class="h-7 w-4 rounded bg-pink-300"></div>
                                        <div class="h-5 w-4 rounded bg-pink-400"></div>
                                        <div class="h-9 w-4 rounded bg-pink-500"></div>
                                        <div class="h-6 w-4 rounded bg-pink-300"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Income</h3>
                            <div class="mt-4 grid h-40 grid-cols-6 items-end gap-2">
                                <div class="h-12 rounded-xl bg-indigo-200"></div>
                                <div class="h-24 rounded-xl bg-indigo-400"></div>
                                <div class="h-16 rounded-xl bg-indigo-300"></div>
                                <div class="h-28 rounded-xl bg-indigo-500"></div>
                                <div class="h-20 rounded-xl bg-indigo-300"></div>
                                <div class="h-32 rounded-xl bg-indigo-400"></div>
                            </div>
                            <button class="mt-5 w-full rounded-full bg-indigo-600 py-2 text-xs font-semibold text-white">Withdraw Earnings</button>
                        </div>

                        <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Summary</h3>
                            <div class="mt-5 space-y-4 text-xs text-gray-500 dark:text-gray-300">
                                <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <span>February</span>
                                        <span class="text-green-500">+3.4%</span>
                                    </div>
                                    <p class="mt-2 text-lg font-semibold text-gray-800 dark:text-gray-100">$13,79</p>
                                </div>
                                <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <span>March</span>
                                        <span class="text-green-500">+5.8%</span>
                                    </div>
                                    <p class="mt-2 text-lg font-semibold text-gray-800 dark:text-gray-100">$24,57</p>
                                </div>
                                <button class="w-full rounded-full border border-indigo-200 py-2 text-xs font-semibold text-indigo-600">Withdraw Earnings</button>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-white p-6 shadow-sm dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-200">Recent Analytics</h3>
                            <span class="text-xs text-gray-400">Last 7 days</span>
                        </div>
                        <div class="mt-4 overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700">
                            <table class="min-w-full text-left text-xs text-gray-500 dark:text-gray-300">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-400 dark:bg-gray-700/40">
                                    <tr>
                                        <th class="px-4 py-3">User</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Amount</th>
                                        <th class="px-4 py-3 text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="px-4 py-3 font-medium text-gray-700 dark:text-gray-200">Sherry Harmon</td>
                                        <td class="px-4 py-3">sheryt3@email.com</td>
                                        <td class="px-4 py-3">$325,54</td>
                                        <td class="px-4 py-3 text-right text-green-500">Accept</td>
                                    </tr>
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="px-4 py-3 font-medium text-gray-700 dark:text-gray-200">Kimberlee Kenzie</td>
                                        <td class="px-4 py-3">kimberlee@email.com</td>
                                        <td class="px-4 py-3">$543,54</td>
                                        <td class="px-4 py-3 text-right text-green-500">Accept</td>
                                    </tr>
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td class="px-4 py-3 font-medium text-gray-700 dark:text-gray-200">Dewey Crowell</td>
                                        <td class="px-4 py-3">dewey@email.com</td>
                                        <td class="px-4 py-3">$214,11</td>
                                        <td class="px-4 py-3 text-right text-amber-500">Pending</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
