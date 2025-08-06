<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

/**
 * Interface ApiRequestInterface
 *
 * @author SOSSOU-GAH Ézéchiel
 * @package Covoiturage
 * @created 2024-08-18
 *
 * Cette interface définit un contrat pour la gestion des règles de validation,
 * des messages d'erreur et des noms d'attributs pour une requête API.
 *
 * Les classes implémentant cette interface devront fournir des méthodes pour
 * retourner les règles de validation, les messages personnalisés ainsi que
 * les noms des attributs à utiliser lors de la validation des données de la requête.
 */
interface ApiRequestInterface
{
    /**
     * Retourne les règles de validation de la requête.
     *
     * Cette méthode doit retourner un tableau associatif où les clés sont les
     * champs de la requête et les valeurs sont les règles de validation associées.
     *
     * @return array Les règles de validation pour les champs de la requête.
     */
    public static function rules(): array;

    /**
     * Retourne les messages de validation de la requête.
     *
     * Cette méthode doit retourner un tableau associatif contenant des messages
     * personnalisés à afficher lors d'une violation des règles de validation.
     *
     * Les clés doivent correspondre à des identifiants de règles spécifiques.
     *
     * @return array Les messages personnalisés pour la validation.
     */
    public static function messages(): array;

    /**
     * Retourne les noms des attributs de la requête.
     *
     * Cette méthode doit retourner un tableau associatif permettant de définir
     * des noms personnalisés pour les champs de la requête afin d'améliorer
     * la lisibilité des messages d'erreur lors de la validation.
     *
     * @return array Les noms personnalisés des attributs de la requête.
     */
    public static function attributes(): array;

    /**
     * Retourne un tableau contenant les règles de validation, les messages d'erreur et les attributs.
     *
     * Cette méthode doit encapsuler les résultats des méthodes rules(), messages()
     * et attributes() dans un seul tableau afin de faciliter la gestion globale
     * des outils de validation pour la requête.
     *
     * @return array Un tableau contenant les règles, messages et attributs pour la validation.
     */
    public static function validationConfigs(): array;

    /**
     * Valide la requête en appliquant les règles définies, les messages d'erreur personnalisés et les attributs.
     *
     * Cette méthode doit retourner une instance de l'objet Validator qui peut être utilisé pour
     * exécuter la validation de la requête. Elle s'appuie sur les configurations de validation définies
     * par les autres méthodes de l'interface.
     *
     * @return Validator L'instance du validateur après application des règles.
     */
    public static function validate(): Validator;

    public static function passedValidation(Request $request): void;

    public static function beforeValidation(Request $request): void;
}
