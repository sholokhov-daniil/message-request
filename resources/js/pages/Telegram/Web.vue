<template>
    <div class="min-h-screen w-screen bg-gradient-to-br from-white to-gray-100 p-4">
        <div class="relative max-w-4xl w-full mx-auto bg-white rounded-2xl shadow-lg p-8">
            <!-- Spinner -->
            <div v-if="loading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
                <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-16 w-16"></div>
            </div>

            <!-- Back button -->
            <button
                v-if="currentStep > 0 && !submitted && !loading"
                @click="prevStep"
                class="absolute left-4 top-4 z-20 flex items-center justify-center w-10 h-10 bg-white rounded-full shadow hover:bg-gray-100 transition-colors"
                aria-label="Назад"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <h2 class="text-3xl font-bold text-center text-gray-800 mb-6 relative z-10">
                {{ submitted ? 'Ваш визит забронирован' : 'Запись в барбершоп' }}
            </h2>

            <!-- Steps 0 & 1 -->
            <div v-if="!submitted && currentStep < 2">
                <div class="flex items-center mb-8">
                    <template v-for="(step, idx) in steps" :key="idx">
                        <div class="flex-1 relative">
                            <div class="h-2 rounded-full" :class="currentStep >= idx ? 'bg-blue-500' : 'bg-gray-300'"></div>
                            <div
                                class="absolute top-1/2 transform -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold"
                                :class="currentStep > idx
                  ? 'bg-blue-500 text-white'
                  : currentStep === idx
                    ? 'bg-white border-2 border-blue-500 text-blue-500'
                    : 'bg-white border-2 border-gray-300 text-gray-400'"
                                :style="{ left: `calc(${(idx/(steps.length-1))*100}% - 1rem)` }"
                            >{{ idx+1 }}</div>
                        </div>
                    </template>
                </div>

                <!-- Step 0: Barbers -->
                <div v-if="currentStep===0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div
                        v-for="barber in barbers" :key="barber.id"
                        class="bg-white p-6 rounded-xl shadow-md cursor-pointer transition hover:shadow-lg hover:scale-105"
                        :class="form.specialist===String(barber.id)?'ring-2 ring-blue-500 bg-blue-50':''"
                        @click="handleSpecialistSelect(barber.id)"
                    >
                        <img :src="barber.photo" class="w-20 h-20 rounded-full mx-auto mb-3 object-cover" alt>
                        <h4 class="font-semibold text-center">{{ barber.name }}</h4>
                        <p class="text-sm text-gray-500 text-center">{{ barber.style }}</p>
                    </div>
                </div>

                <!-- Step 1: Services -->
                <div v-else class="space-y-3 mb-4">
                    <div
                        v-for="svc in filteredServices" :key="svc.id"
                        class="bg-white p-4 rounded-lg shadow-sm cursor-pointer transition hover:shadow-md"
                        :class="form.service===String(svc.id)?'ring-2 ring-blue-500 bg-blue-50':''"
                        @click="handleServiceSelect(svc.id)"
                    >
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="font-semibold">{{ svc.name }}</h4>
                                <p class="text-sm text-gray-500">{{ svc.description }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-blue-600">{{ svc.price }}₽</p>
                                <p class="text-sm">{{ svc.duration }} мин</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Date & Time -->
            <div v-if="!submitted && currentStep===2" class="space-y-6 mb-4">
                <h3 class="text-xl font-semibold mb-4">Дата и время</h3>
                <div v-if="months.length>1" class="mb-4">
                    <select v-model="selectedMonth" class="w-full p-3 border rounded-lg" @change="onMonthChange">
                        <option v-for="m in months" :key="m.value" :value="m.value">{{ m.label }}</option>
                    </select>
                </div>
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div
                        v-for="date in snakeDates" :key="date"
                        class="p-3 rounded-lg text-center cursor-pointer transition hover:shadow-md bg-white shadow-sm text-gray-700"
                        :class="form.date===date?'ring-2 ring-blue-500 bg-blue-50 text-blue-600':''"
                        @click="handleDateSelect(date)"
                    >{{ new Date(date).getDate() }}</div>
                </div>
                <h4 v-if="form.date" class="text-lg font-semibold mb-2">Свободное время</h4>
                <div class="grid grid-cols-3 gap-3">
                    <div
                        v-for="time in availableTimes" :key="time"
                        class="bg-white p-4 rounded-lg shadow-sm cursor-pointer transition hover:shadow-md text-center"
                        :class="form.time===time?'ring-2 ring-blue-500 bg-blue-50 text-blue-600':''"
                        @click="handleTimeSelect(time)"
                    >{{ time }}</div>
                </div>
                <button
                    class="w-full mt-6 px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 transition-colors"
                    @click="finish" :disabled="!(form.date&&form.time)||loading"
                >Записаться</button>
            </div>

            <!-- Confirmation -->
            <div v-if="submitted" class="space-y-6 text-center mt-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <img :src="chosenSpecialist.photo" class="w-24 h-24 rounded-full mx-auto mb-4 object-cover" alt>
                    <h3 class="text-xl font-semibold">{{ chosenSpecialist.name }}</h3>
                    <p class="text-gray-500">{{ chosenSpecialist.style }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold mb-2">Услуга</h4>
                    <p>{{ chosenService.name }}</p>
                    <p class="text-gray-500">{{ chosenService.description }}</p>
                    <p class="mt-2 font-semibold text-blue-600">{{ chosenService.price }}₽ — {{ chosenService.duration }} мин</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md grid grid-cols-2 gap-4">
                    <div><h4 class="font-medium">Дата</h4><p>{{ form.date }}</p></div>
                    <div><h4 class="font-medium">Время</h4><p>{{ form.time }}</p></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import confetti from 'canvas-confetti'

// Barbershop barbers
const barbers = [
    { id: 1, name: 'Алексей', style: 'Классическая стрижка', photo: 'https://i.pravatar.cc/150?img=57' },
    { id: 2, name: 'Дмитрий', style: 'Fade & Beard', photo: 'https://i.pravatar.cc/150?img=11' },
    { id: 3, name: 'Илья',   style: 'Креативные узоры', photo: 'https://i.pravatar.cc/150?img=6' },
]
// Barbershop services
const services = [
    { id:1, specialistId:1, name:'Стрижка', description:'Стрижка машинкой и ножницами', price:1200, duration:30 },
    { id:2, specialistId:1, name:'Коррекция бороды', description:'Оформление и стрижка бороды', price:800, duration:20 },
    { id:3, specialistId:2, name:'Fade', description:'Плавный переход волос', price:1500, duration:40 },
    { id:4, specialistId:2, name:'Fade + борода', description:'Fade и коррекция бороды', price:2000, duration:60 },
    { id:5, specialistId:3, name:'Узор на затылке', description:'Креативный рисунок', price:1800, duration:45 },
]

const blockedDates = [
    '2025-10-02','2025-10-07','2025-10-14','2025-10-21',
    '2025-11-04','2025-11-12','2025-11-19','2025-11-26',
    '2025-12-03','2025-12-10','2025-12-17','2025-12-24'
]

const form = reactive({ specialist:'', service:'', date:'', time:'' })
const steps = [
    { title:'Барбер', field:'specialist' },
    { title:'Услуга', field:'service'    },
    { title:'Дата & Время', field:'date' },
]
const currentStep = ref(0)
const submitted   = ref(false)
const loading     = ref(false)

const filteredServices = computed(() =>
    services.filter(s => String(s.specialistId) === form.specialist)
)
const availableTimes = computed(() =>
    form.date ? ['09:00','10:00','11:00','13:00','14:00','15:00'] : []
)

const months = computed(() => {
    const res = [], today = new Date()
    for (let i=0;i<3;i++){
        const m=new Date(today.getFullYear(), today.getMonth()+i,1)
        const y=m.getFullYear(), mo=m.getMonth()
        const days=new Date(y,mo+1,0).getDate()
        if (Array.from({length:days},(_,d)=>{
            const dt=new Date(y,mo,d+1).toISOString().slice(0,10)
            return !blockedDates.includes(dt)
        }).some(v=>v)){
            res.push({ value:`${y}-${String(mo+1).padStart(2,'0')}`, label:m.toLocaleString('ru-RU',{month:'long',year:'numeric'}) })
        }
    }
    return res
})
const selectedMonth = ref(months.value[0]?.value||'')

const snakeDates = computed(()=>{
    if(!selectedMonth.value) return []
    const [y,mo]=selectedMonth.value.split('-').map(Number)
    const days=new Date(y,mo,0).getDate()
    const free = Array.from({length:days},(_,i)=>{
        const dt=new Date(y,mo-1,i+1).toISOString().slice(0,10)
        return blockedDates.includes(dt)?null:dt
    }).filter(Boolean).slice(0,8)
    const row1=free.slice(0,4), row2=free.slice(4,8).reverse()
    return [...row1,...row2]
})

watch(()=>form.specialist,v=>{ if(v){ form.service=''; form.date=''; form.time=''; currentStep.value=1 } })
watch(()=>form.service,   v=>{ if(v){ form.date=''; form.time=''; currentStep.value=2 } })

function withDelay(fn){
    loading.value = true
    setTimeout(()=>{ fn(); loading.value = false },1000)
}
function handleSpecialistSelect(id){ withDelay(()=> selectSpecialist(id)) }
function handleServiceSelect(id){ withDelay(()=> selectService(id)) }
function handleDateSelect(d){ withDelay(()=> selectDate(d)) }
function handleTimeSelect(t){ withDelay(()=> selectTime(t)) }

function selectSpecialist(id){ form.specialist=String(id) }
function selectService(id)    { form.service   =String(id) }
function selectDate(d)        { form.date      =d; form.time='' }
function selectTime(t)        { form.time      =t }

function prevStep()      { if(currentStep.value>0) currentStep.value-- }
function onMonthChange() { form.date=''; form.time='' }
function finish(){
    loading.value = true
    setTimeout(()=>{
        confetti({ particleCount:150, spread:60 })
        submitted.value = true
        loading.value = false
    },1000)
}

const chosenSpecialist = computed(()=>barbers.find(b=>String(b.id)===form.specialist)||{})
const chosenService    = computed(()=>services.find(s=>String(s.id)===form.service)||{})
</script>

<style scoped>
.loader {
    border-top-color: #3490dc;
    animation: spin 1s infinite linear;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
