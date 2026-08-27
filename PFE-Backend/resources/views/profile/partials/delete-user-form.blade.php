<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Supprimer le compte</h2>
        <p class="mt-1 text-sm text-gray-600">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger les données ou informations que vous souhaitez conserver.</p>
    </header>

    <button type="button" onclick="document.getElementById('confirm-user-deletion').style.display='block'"
        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
        Supprimer le compte
    </button>

    <div id="confirm-user-deletion" style="display:none;">
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-gray-50 rounded-lg">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">Êtes-vous sûr de vouloir supprimer votre compte ?</h2>

            <p class="mt-1 text-sm text-gray-600">
                Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez saisir votre mot de passe pour confirmer la suppression définitive de votre compte.
            </p>

            <div class="mt-6">
                <label for="password" class="block font-medium text-sm text-gray-700">Mot de passe</label>
                <input id="password" name="password" type="password" placeholder="Mot de passe"
                    class="mt-1 block w-3/4 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                @error('password', 'userDeletion')
                    <span class="text-sm text-red-600 mt-2">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('confirm-user-deletion').style.display='none'"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Annuler
                </button>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Supprimer le compte
                </button>
            </div>
        </form>
    </div>
</section>
