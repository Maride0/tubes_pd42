<x-filament-widgets::widget>
    <x-filament::section>
        <div class="overflow-x-auto">

            <!-- Filter -->
            <div class="row">
                <form wire:submit.prevent="filterJurnal" class="flex gap-4 items-center">
                    <div>
                        <label for="periode_awal">Periode Awal:</label>
                        <input type="month" wire:model="periode_awal" id="periode_awal" class="border rounded px-2 py-1">
                    </div>
                    <div>
                        <label for="periode_akhir">Periode Akhir:</label>
                        <input type="month" wire:model="periode_akhir" id="periode_akhir" class="border rounded px-2 py-1">
                    </div>
                    <div>
                        <label for="coa_id">Akun COA:</label>
                        <select wire:model="coa_id" id="coa_id" class="border rounded px-2 py-1">
                            <option value="">-- Pilih Akun --</option>
                            @foreach (\App\Models\Coa::all() as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->kode_akun }} - {{ $akun->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-green-500 text-black px-3 py-1 rounded mt-4">Filter</button>
                    </div>
                </form>

                <br><br>
                <div class="col-sm-12" style="background-color:white;" align="center">
                    <b>Kedai Soto 42</b><br>
                    <b>Buku Besar</b><br>
                    <b>
                    Periode 
                        @if($periode_awal && $periode_akhir)
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $periode_awal)->translatedFormat('F Y') }}
                            -
                            {{ \Carbon\Carbon::createFromFormat('Y-m', $periode_akhir)->translatedFormat('F Y') }}
                        @else
                            {{ now()->translatedFormat('F Y') }}
                        @endif
                    </b>
                </div>
                <br>
            </div>

            @php
                $runningBalance = $saldoAwal;
                $tipeAkun = \App\Models\Coa::find($coa_id)?->tipe ?? 'Debet';
            @endphp
<table class="w-full text-sm text-left border border-gray-200 font-sans">
    <thead class="bg-gray-100 text-xs uppercase">
        <!-- SALDO AWAL -->
        <tr class="font-semibold bg-gray-200">
            <td colspan="6" class="text-right px-4 py-2 border">Saldo Awal</td>
            <td class="text-right px-4 py-2 border">{{ rupiah($saldoAwal) }}</td>
        </tr>
        <!-- HEADER -->
        <tr>
            <th class="px-4 py-2 border">ID Jurnal</th>
            <th class="px-4 py-2 border">Tanggal</th>
            <th class="px-4 py-2 border">Akun</th>
            <th class="px-4 py-2 border">Reff</th>
            <th class="px-4 py-2 border">Debet</th>
            <th class="px-4 py-2 border">Kredit</th>
            <th class="px-4 py-2 border">Balance</th>
        </tr>
    </thead>
    <tbody class="text-xs uppercase">
        @foreach($jurnals as $jurnal)
            @foreach($jurnal->jurnaldetail as $detail)
                <tr>
                    <td class="px-4 py-2 border">{{ $jurnal->id }}</td>
                    <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($jurnal->tgl)->format('Y-m-d') }}</td>

                    @if($detail->debit != 0)
                        <td class="px-4 py-2 border">{{ $detail->coa->nama_akun ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $jurnal->no_referensi }}</td>
                        <td class="px-4 py-2 border text-right">{{ rupiah($detail->debit) }}</td>
                    @else
                        <td class="px-4 py-2 border">&nbsp;&nbsp;&nbsp;{{ $detail->coa->nama_akun ?? '-' }}</td>
                        <td class="px-4 py-2 border">{{ $jurnal->no_referensi }}</td>
                        <td class="px-4 py-2 border text-right"></td>
                    @endif

                    @if($detail->credit != 0)
                        <td class="px-4 py-2 border text-right">{{ rupiah($detail->credit) }}</td>
                    @else
                        <td class="px-4 py-2 border text-right"></td>
                    @endif

                    @php
                        if ($tipeAkun == 'Debet') {
                            $runningBalance += $detail->debit - $detail->credit;
                        } else {
                            $runningBalance += $detail->credit - $detail->debit;
                        }
                    @endphp
                    <td class="px-4 py-2 border text-right">{{ rupiah($runningBalance) }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot class="font-semibold text-xs bg-gray-100">
        @php
            $totalDebit = $jurnals->flatMap->jurnaldetail->sum('debit');
            $totalKredit = $jurnals->flatMap->jurnaldetail->sum('credit');
            $saldoAkhir = $tipeAkun == 'Debet'
                ? $saldoAwal + ($totalDebit - $totalKredit)
                : $saldoAwal + ($totalKredit - $totalDebit);
        @endphp
        <!-- TOTAL -->
        <tr>
            <td colspan="4" class="text-right px-4 py-2 border">Total</td>
            <td class="text-right px-4 py-2 border">{{ rupiah($totalDebit) }}</td>
            <td class="text-right px-4 py-2 border">{{ rupiah($totalKredit) }}</td>
            <td class="border"></td>
        </tr>
        <!-- SALDO AKHIR -->
        <tr class="font-semibold bg-gray-200">
            <td colspan="6" class="text-right px-4 py-2 border">Saldo Akhir</td>
            <td class="text-right px-4 py-2 border">{{ rupiah($saldoAkhir) }}</td>
        </tr>
    </tfoot>
</table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>