<x-dashboard-layout>
    <div class="min-h-screen p-6">
        <div class="mx-auto flex min-h-[calc(100vh-48px)] max-w-[1440px] overflow-hidden rounded-[28px] bg-[#eaf1ff] shadow-[0_30px_70px_rgba(11,92,201,0.35)]">
            <x-dashboard.sidebar />

            <div class="flex flex-1">
                <div class="flex-1 space-y-6 p-6">
                    <x-dashboard.header />

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <x-dashboard.stat-card title="Students" value="1,738" class="bg-[#ffcc66]" />
                        <x-dashboard.stat-card title="Teachers" value="179" class="bg-[#ffd273]" />
                        <x-dashboard.stat-card title="Staffs" value="165" class="bg-[#ffd273]" />
                        <x-dashboard.stat-card
                            title="Awards"
                            value="893"
                            class="bg-[#1677ff]"
                            icon-class="text-white"
                            title-class="text-white/80"
                            value-class="text-white"
                        />
                    </div>

                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[320px_minmax(0,1fr)]">
                        <x-dashboard.chart-card title="Students" action="Grade 7">
                            <div class="flex items-center justify-center gap-6">
                                <div class="relative h-[150px] w-[150px]">
                                    <canvas id="studentsDonut"></canvas>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                                        <span class="text-xs text-[#7d8aa5]">Total</span>
                                        <span class="text-xl font-semibold text-[#1b2b42]">427</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between text-xs font-medium text-[#7d8aa5]">
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full bg-[#ffcc66]"></span>
                                    Girls
                                    <span class="ml-2 text-[#1b2b42]">234</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="h-2.5 w-2.5 rounded-full bg-[#1677ff]"></span>
                                    Boys
                                    <span class="ml-2 text-[#1b2b42]">193</span>
                                </div>
                            </div>
                        </x-dashboard.chart-card>

                        <x-dashboard.chart-card title="Earnings" action="Last 8 Months" class="min-h-[240px]">
                            <div class="h-[190px]">
                                <canvas id="earningsLine"></canvas>
                            </div>
                            <div class="mt-3 flex items-center gap-4 text-xs text-[#7d8aa5]">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-[#ffcc66]"></span>
                                    Earnings
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-[#1677ff]"></span>
                                    Expenses
                                </div>
                            </div>
                        </x-dashboard.chart-card>
                    </div>

                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[minmax(0,1fr)_300px]">
                        <x-dashboard.chart-card title="Attendance" action="Weekly">
                            <div class="h-[160px]">
                                <canvas id="attendanceBar"></canvas>
                            </div>
                            <div class="mt-3 flex items-center gap-4 text-xs text-[#7d8aa5]">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-[#ffcc66]"></span>
                                    Present
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-[#1677ff]"></span>
                                    Absent
                                </div>
                            </div>
                        </x-dashboard.chart-card>

                        <x-dashboard.chart-card title="Student Activities">
                            <div class="space-y-4">
                                <div class="flex gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#1677ff]">
                                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M7 11h10l-3.5 3.5 1.5 1.5L21 10l-6-6-1.5 1.5L17 9H7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-[#1b2b42]">Best in Show at Statewide Art Contest</p>
                                        <p class="text-xs text-[#7d8aa5]">Aiden Kim created a stunning mixed-media landscape piece.</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#ffd273]">
                                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 2 4 7v6c0 5 8 9 8 9s8-4 8-9V7l-8-5z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-[#1b2b42]">Gold Medal in National Math Olympiad</p>
                                        <p class="text-xs text-[#7d8aa5]">Ethan Wong solved complex problems with outstanding skills.</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#1677ff]">
                                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 3 1 9l11 6 9-4.9V17h2V9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-[#1b2b42]">First Place in Regional Science Fair</p>
                                        <p class="text-xs text-[#7d8aa5]">Sophia Martinez innovated a new water purification system.</p>
                                    </div>
                                </div>
                            </div>
                        </x-dashboard.chart-card>
                    </div>

                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[minmax(0,1fr)_320px]">
                        <x-dashboard.chart-card title="Notice Board" action="Latest">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 rounded-2xl border border-[#eef2ff] bg-[#f9fbff] p-4">
                                    <div class="h-10 w-10 overflow-hidden rounded-full bg-[#ffd273]">
                                        <img src="https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=facearea&w=80&h=80" alt="Event" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-[#1b2b42]">School Event Reminder</p>
                                        <p class="text-xs text-[#7d8aa5]">by Mrs. Harper, Event Coordinator</p>
                                    </div>
                                    <span class="text-xs text-[#8b97b1]">May 29, 2025</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl border border-[#eef2ff] bg-[#f9fbff] p-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#1677ff]">
                                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M4 5h16v12H4z" />
                                            <path d="M8 21h8" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-[#1b2b42]">New E-Library Books Available</p>
                                        <p class="text-xs text-[#7d8aa5]">by Mr. Campbell, Librarian</p>
                                    </div>
                                    <span class="text-xs text-[#8b97b1]">May 26, 2025</span>
                                </div>
                            </div>
                        </x-dashboard.chart-card>

                        <x-dashboard.chart-card title="Messages">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3 rounded-2xl bg-[#f5f8ff] p-4">
                                    <div class="h-10 w-10 overflow-hidden rounded-full">
                                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=facearea&w=80&h=80" alt="Alex" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-[#1b2b42]">Alex Campbell</p>
                                        <p class="text-xs text-[#7d8aa5]">Updated lesson plans for review.</p>
                                    </div>
                                    <span class="text-xs text-[#8b97b1]">2:28 PM</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl bg-[#f5f8ff] p-4">
                                    <div class="h-10 w-10 overflow-hidden rounded-full">
                                        <img src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?auto=format&fit=facearea&w=80&h=80" alt="Mia" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-[#1b2b42]">Mia Johnson</p>
                                        <p class="text-xs text-[#7d8aa5]">Parent-teacher meeting notes attached.</p>
                                    </div>
                                    <span class="text-xs text-[#8b97b1]">1:10 PM</span>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl bg-[#f5f8ff] p-4">
                                    <div class="h-10 w-10 overflow-hidden rounded-full">
                                        <img src="https://images.unsplash.com/photo-1544723795-3fb6469f5b39?auto=format&fit=facearea&w=80&h=80" alt="James" class="h-full w-full object-cover" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-[#1b2b42]">James Peterson</p>
                                        <p class="text-xs text-[#7d8aa5]">Sports day schedule updated.</p>
                                    </div>
                                    <span class="text-xs text-[#8b97b1]">11:45 AM</span>
                                </div>
                            </div>
                        </x-dashboard.chart-card>
                    </div>
                </div>

                <aside class="flex w-[300px] flex-col gap-6 border-l border-[#dfe8fb] bg-white p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-[#1b2b42]">July 2025</h2>
                        <div class="flex items-center gap-2 text-[#7d8aa5]">
                            <button class="flex h-7 w-7 items-center justify-center rounded-full border border-[#e5edff]">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                                </svg>
                            </button>
                            <button class="flex h-7 w-7 items-center justify-center rounded-full border border-[#e5edff]">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center text-[11px] text-[#7d8aa5]">
                        <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                    </div>
                    <div class="grid grid-cols-7 gap-2 text-center text-xs font-medium text-[#1b2b42]">
                        <span class="text-[#aab4c8]">29</span>
                        <span class="text-[#aab4c8]">30</span>
                        <span class="text-[#aab4c8]">1</span>
                        <span>2</span>
                        <span>3</span>
                        <span>4</span>
                        <span>5</span>
                        <span>6</span>
                        <span>7</span>
                        <span>8</span>
                        <span>9</span>
                        <span class="rounded-full bg-[#1677ff] px-1.5 py-0.5 text-white">10</span>
                        <span>11</span>
                        <span>12</span>
                        <span>13</span>
                        <span>14</span>
                        <span>15</span>
                        <span>16</span>
                        <span>17</span>
                        <span>18</span>
                        <span>19</span>
                        <span>20</span>
                        <span>21</span>
                        <span>22</span>
                        <span>23</span>
                        <span>24</span>
                        <span>25</span>
                        <span>26</span>
                        <span>27</span>
                        <span>28</span>
                        <span>29</span>
                        <span>30</span>
                        <span>31</span>
                        <span class="text-[#aab4c8]">1</span>
                        <span class="text-[#aab4c8]">2</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-[#1b2b42]">Upcoming Events</h3>
                        <button class="text-xs text-[#7d8aa5]">•••</button>
                    </div>
                    <div class="space-y-4">
                        <x-dashboard.event-item date="15 July" time="7:00 AM - 8:00 AM" title="New Student Inauguration Ceremony" grade="Grade 7" />
                        <x-dashboard.event-item date="19 July" time="10:00 AM - 11:00 AM" title="Chairman of Student Body Handover" grade="Grade 8" badge-class="bg-[#ffe2a8] text-[#1b2b42]" />
                        <x-dashboard.event-item date="27 July" time="3:00 PM" title="Closing of School Clubs Acceptance" grade="Grade 7" badge-class="bg-[#ffbf4d] text-[#1b2b42]" />
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        const donutContext = document.getElementById('studentsDonut');
        if (donutContext) {
            new Chart(donutContext, {
                type: 'doughnut',
                data: {
                    labels: ['Girls', 'Boys'],
                    datasets: [
                        {
                            data: [234, 193],
                            backgroundColor: ['#ffcc66', '#1677ff'],
                            borderWidth: 0,
                            hoverOffset: 4,
                        },
                    ],
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                    },
                },
            });
        }

        const lineContext = document.getElementById('earningsLine');
        if (lineContext) {
            new Chart(lineContext, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],
                    datasets: [
                        {
                            label: 'Earnings',
                            data: [45, 60, 55, 80, 70, 95, 85, 110],
                            borderColor: '#ffcc66',
                            backgroundColor: 'rgba(255, 204, 102, 0.15)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                        },
                        {
                            label: 'Expenses',
                            data: [35, 45, 40, 60, 55, 70, 65, 90],
                            borderColor: '#1677ff',
                            backgroundColor: 'rgba(22, 119, 255, 0.12)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9aa7c2', font: { size: 10 } },
                        },
                        y: {
                            grid: { color: '#edf2ff' },
                            ticks: {
                                color: '#9aa7c2',
                                font: { size: 10 },
                                callback: (value) => `${value}K`,
                            },
                            beginAtZero: true,
                        },
                    },
                },
            });
        }

        const barContext = document.getElementById('attendanceBar');
        if (barContext) {
            new Chart(barContext, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    datasets: [
                        {
                            label: 'Present',
                            data: [80, 75, 90, 70, 85],
                            backgroundColor: '#ffcc66',
                            borderRadius: 12,
                            barThickness: 18,
                        },
                        {
                            label: 'Absent',
                            data: [20, 25, 10, 30, 15],
                            backgroundColor: '#1677ff',
                            borderRadius: 12,
                            barThickness: 18,
                        },
                    ],
                },
                options: {
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#9aa7c2', font: { size: 10 } },
                        },
                        y: {
                            grid: { color: '#edf2ff' },
                            ticks: {
                                color: '#9aa7c2',
                                font: { size: 10 },
                                callback: (value) => `${value}%`,
                            },
                            beginAtZero: true,
                            max: 100,
                        },
                    },
                },
            });
        }
    </script>
</x-dashboard-layout>
