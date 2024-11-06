<?php

namespace App\Domain\Interfaces\Configuration;

use App\Models\Role;
use Illuminate\Http\Request;

interface IConfigurationService
{
    // Define your service interface methods here


    #region roles
        public function getRoles();
        public function getRole(int $id);
        public function createRole(Request $request);
        public function updateRole(Request $request);
        public function deleteRole(int $id);
    #endregion

    #region abilities
        public function getAbilities();
        public function getAbility(int $id);
        public function getByRoleId(int $roleId);
        public function createAbility(Request $request);
        public function updateAbility(Request $request);
        public function deleteAbility(int $id);
    #endregion

    #region prefix
        public function getPrefixes();
        public function getPrefix(int $id);
        public function createPrefix(Request $request);
        public function updatePrefix(Request $request);
        public function deletePrefix(int $id);
    #endregion

    #region general_middlewares
        public function getGeneralMiddlewares();
        public function getGeneralMiddleware(int $id);
        public function createGeneralMiddleware(Request $request);
        public function updateGeneralMiddleware(Request $request);
        public function deleteGeneralMiddleware(int $id);
    #endregion

    #region controllers
        public function getControllers();
        public function getController(int $id);
        public function createController(Request $request);
        public function updateController(Request $request);
        public function deleteController(int $id);
    #endregion

    #region routes
        public function getRoutes();
        public function getRoute(int $id);
        public function createRoute(Request $request);
        public function updateRoute(Request $request);
        public function deleteRoute(int $id);
    #endregion

    #region sub_middlewares
        public function getSubMiddlewares();
        public function getSubMiddleware(int $id);
        public function createSubMiddleware(Request $request);
        public function updateSubMiddleware(Request $request);
        public function deleteSubMiddleware(int $id);
    #endregion'

    #region country
        public function getCountries();
        public function getCountry(int $id);
        public function createCountry(Request $request);
        public function updateCountry(Request $request);
        public function deleteCountry(int $id);
    #endregion

        public function setTimezone(Request $request);

    #region token
        public function getTokens();
        public function deleteToken(int $id);
    #endregion
}
