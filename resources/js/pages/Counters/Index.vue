<script setup lang="ts">
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { PlusCircle, MinusCircle, Trash2 } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: '计数器',
        href: '/counters',
    },
];

interface Counter {
    id: number;
    name: string;
    value: number;
    step: number;
}

defineProps<{
    counters: Counter[];
}>();

const newCounter = ref({
    name: '',
    value: 0,
    step: 1,
});

function createCounter() {
    router.post('/counters', newCounter.value, {
        onSuccess: () => {
            newCounter.value = {
                name: '',
                value: 0,
                step: 1,
            };
        },
    });
}

function increment(counter: Counter) {
    router.post(`/counters/${counter.id}/increment`);
}

function decrement(counter: Counter) {
    router.post(`/counters/${counter.id}/decrement`);
}

function updateCounter(counter: Counter) {
    router.put(`/counters/${counter.id}`, {
        name: counter.name,
        value: counter.value,
        step: counter.step
    });
}

function deleteCounter(counter: Counter) {
    if (confirm('确定要删除这个计数器吗？')) {
        router.delete(`/counters/${counter.id}`);
    }
}
</script>

<template>

    <Head title="计数器" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col flex-1 h-full gap-4 p-4 rounded-xl">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <!-- 左侧：创建新计数器 -->
                <div class="col-span-1">
                    <Card>
                        <CardHeader>
                            <CardTitle>创建新计数器</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <form @submit.prevent="createCounter">
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">名称</label>
                                        <Input id="name" v-model="newCounter.name" required class="mt-1" />
                                    </div>

                                    <div>
                                        <label for="value" class="block text-sm font-medium text-gray-700">初始值</label>
                                        <Input id="value" v-model.number="newCounter.value" type="number"
                                            class="mt-1" />
                                    </div>

                                    <div>
                                        <label for="step" class="block text-sm font-medium text-gray-700">步长</label>
                                        <Input id="step" v-model.number="newCounter.step" type="number" min="1"
                                            class="mt-1" />
                                    </div>

                                    <Button type="submit" class="w-full">创建计数器</Button>
                                </div>
                            </form>
                        </CardContent>
                    </Card>
                </div>

                <!-- 右侧：计数器列表 -->
                <div class="col-span-1 md:col-span-3">
                    <Card>
                        <CardHeader>
                            <CardTitle>我的计数器</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="counters.length === 0" class="py-4 text-center text-gray-500">
                                暂无计数器，请创建一个新的计数器。
                            </div>

                            <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <Card v-for="counter in counters" :key="counter.id" class="overflow-hidden">
                                    <CardHeader class="bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <CardTitle class="text-base truncate">{{ counter.name }}</CardTitle>
                                            <Button variant="ghost" size="icon" @click="deleteCounter(counter)">
                                                <Trash2 class="w-4 h-4" />
                                            </Button>
                                        </div>
                                    </CardHeader>
                                    <CardContent class="py-4">
                                        <div class="flex items-center justify-between">
                                            <Button variant="outline" size="icon" @click="decrement(counter)">
                                                <MinusCircle class="w-4 h-4" />
                                            </Button>

                                            <div class="text-3xl font-bold">
                                                {{ counter.value }}
                                            </div>

                                            <Button variant="outline" size="icon" @click="increment(counter)">
                                                <PlusCircle class="w-4 h-4" />
                                            </Button>
                                        </div>
                                    </CardContent>
                                    <CardFooter class="flex items-center justify-between py-2 bg-gray-50">
                                        <div class="text-sm text-gray-500">步长:</div>
                                        <div>
                                            <Input v-model.number="counter.step" type="number" min="1"
                                                class="w-16 text-sm h-7" @change="updateCounter(counter)" />
                                        </div>
                                    </CardFooter>
                                </Card>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>