<?php

namespace App\Decorators;

use App\Contracts\ApiRequestInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

/**
 * @author SOSSOU-GAH Ézéchiel
 * @package Covoiturage
 * @created 2024-08-18
 *
 * Classe abstraite ApiRequestDecorator
 *
 * Cette classe implémente l'interface ApiRequestInterface et fournit des implémentations
 * par défaut pour les méthodes définies dans cette interface. Elle utilise le pattern
 * du "décorateur", permettant aux classes enfants de modifier ou d'étendre ces comportements.
 *
 * Les méthodes retournent des tableaux vides par défaut, mais elles peuvent être redéfinies
 * dans les classes concrètes qui héritent de cette classe abstraite.
 */
abstract class ApiRequestDecorator implements ApiRequestInterface
{
    /**
     * Retourne les règles de validation de la requête.
     *
     * Cette méthode abstraite doit être implémentée dans les classes concrètes héritant de cette classe.
     * Elle doit retourner un tableau associatif où les clés sont les noms des champs de la requête et
     * les valeurs sont les règles de validation associées à ces champs.
     *
     * Cette méthode permet de définir les contraintes qui seront appliquées aux données de la requête
     * lors de la validation. Les classes concrètes peuvent personnaliser les règles en fonction des
     * exigences spécifiques de la requête.
     *
     * @return array Un tableau associatif des règles de validation pour les champs de la requête.
     */
    abstract public static function rules(): array;


    /**
     * Retourne les messages de validation de la requête.
     *
     * Cette méthode retourne un tableau vide par défaut. Les classes enfants peuvent
     * la redéfinir pour retourner des messages de validation personnalisés.
     *
     * @return array
     */
    public static function messages(): array
    {
        return [];
    }

    /**
     * Retourne les noms des attributs de la requête.
     *
     * Cette méthode abstraite doit être implémentée dans les classes concrètes qui héritent
     * de cette classe. Elle doit retourner un tableau associatif où les clés sont les noms
     * des champs de la requête et les valeurs sont les noms humanisés des attributs.
     *
     * Cela permet de personnaliser les noms des champs de la requête dans les messages
     * d'erreur de validation, offrant ainsi une meilleure lisibilité et compréhension
     * des erreurs par l'utilisateur final.
     *
     * @return array Un tableau associatif des noms d'attributs personnalisés.
     */
    abstract public static function attributes(): array;


    /**
     * Retourne un tableau contenant les règles de validation, les messages d'erreur et les attributs.
     *
     * Cette méthode agrège les résultats des méthodes rules(), messages() et attributes()
     * en un seul tableau, permettant une gestion unifiée des outils de validation.
     * Elle peut être redéfinie pour fournir des configurations de validation supplémentaires.
     *
     * @return array
     */
    public static function validationConfigs(): array
    {
        return [
            static::rules(),
            static::messages(),
            static::attributes(),
        ];
    }

    /**
     * Valide la requête HTTP en utilisant les règles de validation définies, les messages personnalisés et les attributs.
     *
     * Cette méthode utilise le validateur de Laravel pour valider les données de la requête HTTP.
     * Si aucune requête n'est passée en paramètre, elle utilise la requête globale actuelle.
     * La validation repose sur les configurations de validation agrégées par la méthode validationTools().
     *
     * @param Request|null $request La requête à valider, facultative. Si elle est omise, la requête actuelle est utilisée.
     * @return Validator Le validateur contenant les règles appliquées à la requête.
     */
    public static function validate(?Request $request = null): Validator
    {
        $request = ($request ?? request());
        static::beforeValidation($request);
        $validator = validator($request->all(), ...self::validationConfigs());
        static::passedValidation($request);

        return $validator;
    }

    public static function passedValidation(Request $request): void {}

    public static function beforeValidation(Request $request): void {}
}
