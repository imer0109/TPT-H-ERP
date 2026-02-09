@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-xl font-semibold mb-4">Créer un bon de commande</h1>
    <form method="post" action="{{ route('fournisseurs.orders.store') }}" onsubmit="return false;" class="bg-white p-6 rounded shadow space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Fournisseur</label>
                <select name="fournisseur_id" id="fournisseur_id" class="form-select w-full">
                    <option value="">-- Sélectionner --</option>
                    @foreach($fournisseurs as $id => $rs)
                        <option value="{{ $id }}">{{ $rs }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Date</label>
                <input type="date" name="date_commande" id="date_commande" class="form-input w-full" value="{{ date('Y-m-d') }}" />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Devise</label>
                <input type="text" name="devise" id="devise" class="form-input w-full" value="XOF" />
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Lignes</label>
            <table class="min-w-full" id="itemsTable">
                <thead>
                    <tr>
                        <th class="p-2 text-left">Produit</th>
                        <th class="p-2 text-left">Désignation</th>
                        <th class="p-2 text-right">Quantité</th>
                        <th class="p-2">Unité</th>
                        <th class="p-2 text-right">Prix</th>
                        <th class="p-2 text-right">Total</th>
                        <th class="p-2"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button type="button" class="btn btn-sm mt-2" onclick="addRow()">+ Ajouter une ligne</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2"></div>
            <div class="bg-gray-50 p-4 rounded">
                <div class="flex justify-between"><span>Montant HT</span><span id="mt_ht">0</span></div>
                <div class="flex justify-between"><span>TVA</span><span id="mt_tva">0</span></div>
                <div class="flex justify-between font-semibold"><span>Total TTC</span><span id="mt_ttc">0</span></div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" class="btn btn-primary" onclick="submitOrder()">Enregistrer</button>
            <a href="{{ route('fournisseurs.orders.index') }}" class="btn">Annuler</a>
        </div>
    </form>

    <script>
        const products = @json($products);
        function addRow() {
            const tbody = document.querySelector('#itemsTable tbody');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="p-2">
                    <select class="form-select w-full item_product">
                        <option value="">--</option>
                        ${Object.entries(products).map(([id,name])=>`<option value="${id}">${name}</option>`).join('')}
                    </select>
                </td>
                <td class="p-2"><input type="text" class="form-input w-full item_designation" placeholder="Désignation"/></td>
                <td class="p-2"><input type="number" min="0" step="0.001" class="form-input w-full text-right item_qte" value="1"/></td>
                <td class="p-2"><input type="text" class="form-input w-full item_unite" value="U"/></td>
                <td class="p-2"><input type="number" min="0" step="0.01" class="form-input w-full text-right item_prix" value="0"/></td>
                <td class="p-2 text-right item_total">0</td>
                <td class="p-2"><button type="button" class="btn btn-sm" onclick="this.closest('tr').remove(); calcTotals();">Supprimer</button></td>
            `;
            tbody.appendChild(tr);
            tr.querySelectorAll('.item_qte,.item_prix').forEach(inp=>inp.addEventListener('input',calcTotals));
            calcTotals();
        }
        function calcTotals(){
            let ht=0; document.querySelectorAll('#itemsTable tbody tr').forEach(tr=>{
                const q = parseFloat(tr.querySelector('.item_qte').value)||0;
                const p = parseFloat(tr.querySelector('.item_prix').value)||0;
                const t = q*p; tr.querySelector('.item_total').textContent = t.toFixed(2); ht+=t;
            });
            document.getElementById('mt_ht').textContent = ht.toFixed(2);
            document.getElementById('mt_tva').textContent = (0).toFixed(2);
            document.getElementById('mt_ttc').textContent = ht.toFixed(2);
        }
        function submitOrder(){
            const items=[]; document.querySelectorAll('#itemsTable tbody tr').forEach(tr=>{
                items.push({
                    product_id: tr.querySelector('.item_product').value||null,
                    designation: tr.querySelector('.item_designation').value,
                    quantite: tr.querySelector('.item_qte').value,
                    unite: tr.querySelector('.item_unite').value,
                    prix_unitaire: tr.querySelector('.item_prix').value,
                });
            });
            fetch("{{ route('fournisseurs.orders.store') }}",{
                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: JSON.stringify({
                    fournisseur_id: document.getElementById('fournisseur_id').value,
                    date_commande: document.getElementById('date_commande').value,
                    devise: document.getElementById('devise').value,
                    items: items
                })
            }).then(r=>{ if(r.redirected){ window.location = r.url; } else return r.text(); }).then(()=>{});
        }
        addRow();
    </script>
    <div class="mt-4">
        <a href="{{ route('fournisseurs.orders.index') }}" class="text-primary-600">Retour</a>
    </div>
</div>
@endsection


