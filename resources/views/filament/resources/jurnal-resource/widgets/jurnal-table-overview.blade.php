<x-filament-widgets::widget>
    <x-filament::section>
        
        <div class="overflow-x-auto">

            <!-- Filter Periode Jurnal -->
            <!-- Akhir Filter Periode Jurnal-->

            <!-- Tambahan filter -->
            <div class="row">

          
            <form wire:submit.prevent="filterJurnal">
                <label for="periode">Pilih Periode:</label>
                <input type="month" wire:model="periode" id="periode" class="border rounded px-2 py-1">
                <button type="submit" class="ml-2 bg-green-500 text-black px-3 py-1 rounded">Filter</button>
            </form>

            
                <br><br>
               
                <div class="col-sm-12" style="background-color:white;" align="center">
                    <b>Kedai Soto 42</b><br>
                    <b>Jurnal Umum</b><br>
                    <b>Periode {{ $periode ? \Carbon\Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y') : now()->translatedFormat('F Y') }} </b><br>
                </div>
               <br>
            </div>
            <!-- Akhir Tambahan Filter -->

            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2 border">ID Jurnal</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Akun</th>
                        <th class="px-4 py-2 border">Reff</th>
                        <th class="px-4 py-2 border">Debet</th>
                        <th class="px-4 py-2 border">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jurnals as $jurnal)
                        @foreach($jurnal->jurnaldetail as $detail)
                            <tr>
                                <td class="px-4 py-2 border">{{ $jurnal->id }}</td>
                                <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($jurnal->tgl)->format('Y-m-d') }}</td>

                                {{-- Akun --}}
                                <td class="px-4 py-2 border">
                                    @if($detail->debit != 0)
                                        {{ $detail->coa->nama_akun ?? '-' }}
                                    @else
                                        &nbsp;&nbsp;&nbsp;{{ $detail->coa->nama_akun ?? '-' }}
                                    @endif
                                </td>

                                {{-- Reff: Kode Akun --}}
                                <td class="px-4 py-2 border">{{ $detail->coa->kode_akun ?? '-' }}</td>

                                {{-- Debet --}}
                                <td class="px-4 py-2 border text-right">
                                    {{ $detail->debit != 0 ? rupiah($detail->debit) : '' }}
                                </td>

                                {{-- Kredit --}}
                                <td class="px-4 py-2 border text-right">
                                    {{ $detail->credit != 0 ? rupiah($detail->credit) : '' }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold bg-gray-100">
                        <td colspan="4" class="text-right px-4 py-2 border">Total</td>
                        <td class="text-right px-4 py-2 border">
                            {{ rupiah($jurnals->flatMap->jurnaldetail->sum('debit')) }}
                        </td>
                        <td class="text-right px-4 py-2 border">
                            {{ rupiah($jurnals->flatMap->jurnaldetail->sum('credit')) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>


    </x-filament::section>
</x-filament-widgets::widget>
