<form id="myForm"  action="https://181.59.41.171:6443/crtcmrpocpsv/home-high/" method="post" target="_top">
        <input type="hidden" name="idapp" value="CO_ASOB_WB" />
        <input type="hidden" name="seckey" value="{{$access_token}}" />
        <input type="hidden" name="country" value="CO" />
        <input type="hidden" name="idmaqc_service" value="AUTHENTICATE_DIGITALPLATFORM" />
        <input type="hidden" name="profile_services" value="POC" />
        <input type="hidden" name="services" value="LIVENESS_ACTIVE" />
        <input type="hidden" name="externaltxid" value="0222557907" />
        <input type="hidden" name="dni" value="{{$cedula}}" />
        <input type="hidden" name="operacion" value="FS000014" />
        <button type="submit" class="buttonSubmitHide">Iniciar Autenticacion</button>
    </form>