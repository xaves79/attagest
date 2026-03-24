<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\DashboardAchats;
use App\Livewire\DashboardStocks;
use App\Livewire\DashboardProduction;
use App\Livewire\DashboardVentes;
use App\Livewire\Entreprises;
use App\Livewire\VarietesRice;
use App\Livewire\Articles;
use App\Livewire\Localites;
use App\Livewire\PointsVente;
use App\Livewire\Clients;
use App\Livewire\Postes;
use App\Livewire\Agents;
use App\Livewire\Fournisseurs;
use App\Livewire\AchatsPaddy;
use App\Livewire\StocksPaddy;
use App\Livewire\Etuvages;
use App\Livewire\LotsRizEtuve;
use App\Livewire\NouvelDecorticage;
use App\Livewire\ListeDecorticages;
use App\Livewire\Decorticages;
use App\Livewire\DashboardFinancier;
use App\Livewire\StocksProduitsFinis;
use App\Livewire\SacsProduitsFinis;
use App\Livewire\TransfertsPointsVente;
use App\Livewire\TraitementsClients;
use App\Livewire\Ventes;
use App\Livewire\FacturesClients;
use App\Livewire\LignesFacture;
use App\Livewire\LignesRecuFournisseur;
use App\Livewire\PaiementsFactures;
use App\Livewire\RecusFournisseursCrud;
use App\Livewire\DetailsRecusFournisseurs;
use App\Livewire\PaiementsFournisseursCrud;
use App\Livewire\Comptes;
use App\Livewire\EcrituresComptables;
use App\Livewire\Comptabilite;
use App\Livewire\Reservoirs;
use App\Livewire\MouvementsReservoirs;
use App\Livewire\BilansGlobaux;
use App\Livewire\Rapports;
use App\Livewire\EcrituresComptablesForm;
use App\Livewire\PiecesComptables;
use App\Livewire\NouvelleCommande;
use App\Livewire\ListeCommandes;
use App\Livewire\ShowCommande;
use App\Livewire\LivrerCommande;
use App\Livewire\VenteAnticipation;
use App\Livewire\NouvelAchat;
use App\Livewire\ListeAchats;
use App\Livewire\NouvelEtuvage;
use App\Livewire\ListeEtuvages;
use App\Livewire\Ensachage;
use App\Livewire\ParametresApp;
use App\Livewire\GestionPrix;
use App\Livewire\JournalIntervenants;
use App\Livewire\GestionUtilisateurs;
use App\Livewire\Documentation;
use App\Http\Controllers\RecuFournisseurController;
use App\Http\Controllers\ComptabiliteExportController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\TraitementController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {

    // ----------------------------------------------------------------
    // Dashboards
    // ----------------------------------------------------------------
    Route::get('/dashboard',            Dashboard::class)->name('dashboard');
    Route::get('/dashboard-entreprise', \App\Livewire\DashboardEntreprise::class)->name('dashboard.entreprise');
    Route::get('/dashboard-achats',     DashboardAchats::class)->name('dashboard.achats');
    Route::get('/dashboard-stocks',     DashboardStocks::class)->name('dashboard.stocks');
    Route::get('/dashboard-production', DashboardProduction::class)->name('dashboard.production');
    Route::get('/dashboard-ventes',     DashboardVentes::class)->name('dashboard.ventes');
    Route::get('/dashboard-financier',  DashboardFinancier::class)->name('dashboard.financier');

    // ----------------------------------------------------------------
    // Référentiels
    // ----------------------------------------------------------------
    Route::get('/entreprises',   Entreprises::class)->name('entreprises.index');
    Route::get('/varietes',      VarietesRice::class)->name('varietes.index');
    Route::get('/articles',      Articles::class)->name('articles.index');
    Route::get('/localites',     Localites::class)->name('localites.index');
    Route::get('/points-vente',  PointsVente::class)->name('points-vente');
    Route::get('/clients',       Clients::class)->name('clients.index');
    Route::get('/postes',        Postes::class)->name('postes.index');
    Route::get('/agents',        Agents::class)->name('agents');
    Route::get('/fournisseurs',  Fournisseurs::class)->name('fournisseurs.index');

    // ----------------------------------------------------------------
    // Achats & fournisseurs
    // ----------------------------------------------------------------
    Route::get('/achats-paddy',            AchatsPaddy::class)->name('achats-paddy.index');
    Route::get('/recus-fournisseurs-crud', RecusFournisseursCrud::class)->name('recus-fournisseurs.crud');
    Route::get('/paiements-fournisseurs',  PaiementsFournisseursCrud::class)->name('paiements-fournisseurs.index');
    Route::get('/paiements-fournisseurs/recu/{id}', PaiementsFournisseursCrud::class)->name('paiements-fournisseurs.recu');
    Route::get('/lignes-recu-fournisseur', LignesRecuFournisseur::class)->name('lignes-recu-fournisseur.index');
    Route::get('/details-recus-fournisseurs', DetailsRecusFournisseurs::class)->name('details-recus-fournisseurs');
	Route::get('/achats',          ListeAchats::class)->name('achats.index');
	Route::get('/achats/nouvelle', NouvelAchat::class)->name('achats.nouvelle');
	Route::get('/achats/{id}',     \App\Livewire\ShowAchat::class)->name('achats.show');
	Route::get('/recus/{id}/imprimer', [RecuFournisseurController::class, 'imprimer'])->name('recus.imprimer');

    // ----------------------------------------------------------------
    // Stocks & logistique
    // ----------------------------------------------------------------
    Route::get('/stocks-paddy',        StocksPaddy::class)->name('stocks-paddy.index');
    Route::get('/stocks-produits-finis', StocksProduitsFinis::class)->name('stocks-produits-finis');
    Route::get('/sacs-produits-finis', SacsProduitsFinis::class)->name('sacs-produits-finis');
    Route::get('/stocks-sacs',         \App\Livewire\StocksSacs::class)->name('stocks-sacs');
    Route::get('/mouvements-sacs',     \App\Livewire\MouvementsSacs::class)->name('mouvements-sacs');
    Route::get('/transferts-points-vente', TransfertsPointsVente::class)->name('transferts-points-vente');
    Route::get('/reservoirs',          Reservoirs::class)->name('reservoirs');
    Route::get('/mouvements-reservoirs', MouvementsReservoirs::class)->name('mouvements-reservoirs');

    // ----------------------------------------------------------------
    // Transformation
    // ----------------------------------------------------------------
    Route::get('/etuvages',           Etuvages::class)->name('etuvages');
    Route::get('/lots-riz-etuve',     LotsRizEtuve::class)->name('lots-riz-etuve');
    Route::get('/decorticages',       Decorticages::class)->name('decorticages');
    Route::get('/traitements-clients', TraitementsClients::class)->name('traitements-clients');
    Route::get('/paiements-traitements', \App\Livewire\PaiementsTraitement::class)->name('paiements-traitements');
	Route::get('/traitements/{id}/imprimer', [TraitementController::class, 'imprimer'])->name('traitements.imprimer');
	
	Route::get('/etuvages',          ListeEtuvages::class)->name('etuvages.index');
	Route::get('/etuvages/nouvelle', NouvelEtuvage::class)->name('etuvages.nouvelle');
	
	Route::get('/decorticages/nouvelle', NouvelDecorticage::class)->name('decorticages.nouvelle');
	Route::get('/decorticages',          ListeDecorticages::class)->name('decorticages.index');
	
	Route::get('/ensachage', Ensachage::class)->name('ensachage.index');

    // ----------------------------------------------------------------
    // Ventes & facturation
    // ----------------------------------------------------------------
    Route::get('/ventes',             Ventes::class)->name('ventes');
    Route::get('/factures-clients',   FacturesClients::class)->name('factures-clients');
    Route::get('/lignes-facture',     LignesFacture::class)->name('lignes-facture');
    Route::get('/paiements-factures', PaiementsFactures::class)->name('paiements-factures');

    // Commandes — ordre IMPORTANT : routes fixes avant routes dynamiques
    Route::get('/commandes',                  ListeCommandes::class)->name('commandes.index');
    Route::get('/commandes/nouvelle',         NouvelleCommande::class)->name('commandes.nouvelle');
    Route::get('/commandes/anticipation',     VenteAnticipation::class)->name('commandes.anticipation');
    Route::get('/commandes/{id}/livrer',      LivrerCommande::class)->name('commandes.livrer');
    Route::get('/commandes/{id}',             ShowCommande::class)->name('commandes.show');

    // Facture PDF
    Route::get('/factures/{id}/imprimer', [FactureController::class, 'imprimer'])->name('factures.imprimer');

    // ----------------------------------------------------------------
    // Comptabilité
    // ----------------------------------------------------------------
    Route::get('/comptes',              Comptes::class)->name('comptes');
    Route::get('/ecritures-comptables', EcrituresComptables::class)->name('ecritures-comptables');
    Route::get('/ecritures-comptables/create', EcrituresComptablesForm::class);
    Route::get('/pieces-comptables',    PiecesComptables::class)->name('pieces-comptables');
	Route::get('/comptabilite', Comptabilite::class)->name('comptabilite.index');

    // ----------------------------------------------------------------
    // Rapports & bilans
    // ----------------------------------------------------------------
    Route::get('/bilans-globaux', BilansGlobaux::class)->name('bilans.globaux');
    Route::get('/rapports',       Rapports::class)->name('rapports');
	
	// ----------------------------------------------------------------
    // Paramètres
    // ----------------------------------------------------------------
	Route::get('/parametres', ParametresApp::class)->name('parametres.index');
	Route::get('/journal-intervenants', JournalIntervenants::class)->name('journal.intervenants');
	Route::get('/gestion-utilisateurs', GestionUtilisateurs::class)->name('gestion.utilisateurs');
	Route::get('/documentation', Documentation::class)->name('documentation');
	
	// ----------------------------------------------------------------
    // Gestion prix
    // ----------------------------------------------------------------
	Route::get('/parametres/prix', GestionPrix::class)->name('parametres.prix');
    // ----------------------------------------------------------------
    // Admin
    // ----------------------------------------------------------------
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/admin-panel', \App\Livewire\AdminPanel::class)->name('admin.panel');
    });

    // Auth
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ----------------------------------------------------------------
// Routes publiques
// ----------------------------------------------------------------
Route::get('/test', function () {
    return view('test');
});

// ----------------------------------------------------------------
// Auth (guest)
// ----------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login',  fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register',  fn() => view('auth.register'))->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

    Route::get('/forgot-password',   [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password',  [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',        [ResetPasswordController::class, 'reset'])->name('password.update');
});