<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CounterController extends Controller
{
    /**
     * 显示计数器列表
     */
    public function index()
    {
        $counters = Counter::all();

        return Inertia::render('Counters/Index', [
            'counters' => $counters,
        ]);
    }

    /**
     * 保存新计数器
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|integer',
            'step' => 'nullable|integer',
        ]);

        $counter = Counter::create($validated);

        return redirect()->route('counters.index');
    }

    /**
     * 更新计数器
     */
    public function update(Request $request, Counter $counter)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'value' => 'sometimes|required|integer',
            'step' => 'sometimes|required|integer',
        ]);

        $counter->update($validated);

        return redirect()->route('counters.index');
    }

    /**
     * 删除计数器
     */
    public function destroy(Counter $counter)
    {
        $counter->delete();

        return redirect()->route('counters.index');
    }

    /**
     * 增加计数器值
     */
    public function increment(Counter $counter)
    {
        $counter->update([
            'value' => $counter->value + $counter->step
        ]);

        return redirect()->route('counters.index');
    }

    /**
     * 减少计数器值
     */
    public function decrement(Counter $counter)
    {
        $counter->update([
            'value' => $counter->value - $counter->step
        ]);

        return redirect()->route('counters.index');
    }
}
