<template>
<div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						Panel de Firma
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<span>
										<img :src="getImgUrl(filename)" frameborder="0" width="200" height="100%" style="display: block; margin-left: auto;margin-right: auto;"	></img>
								</span>
							</div>
							<div class="col-md-6">
								<br>
								<div class="row">
									<div class="col-md-6">
										Sección:
									</div>
									<div class="col-md-6">
										<input v-model="seccion" type="number" name="seccion" min="1" max="8" size="1">
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<b>AJUSTE EN SECCIÓN</b>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										Horizontal (%): 
									</div>
									<div class="col-md-6">
										<input v-model="horizontal" type="number" name="horizontal" min="0" max="100" size="3" step="10">
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										Vertical (%):
									</div> 
									<div class="col-md-6">
										<input v-model="vertical" type="number" name="vertical" min="0" max="100" size="3" step="10">
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<b>INSERCIÓN EN HOJAS:</b>
									</div>
								</div>
 								<div class="row">
									<div class="col-md-12">
										<select v-model="hojas" class="select form-control">
										  <option v-for="option in options" v-bind:value="option.value">
										    {{ option.text }}
										  </option>
										</select>
									</div>
								</div>
								<div>
									<span v-if=" hojas == 'rango' ">
											<div class="row">
												<div class="col-md-12">
													Rango:
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<input v-model="range_page" name="rango" id="rango" type="text" class="form-control">
												</div>
											</div>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-2">
								Firma:
							</div>
							<div class="col-md-3">
								<span>
									<img :src="getImgUrl(filefirma)" frameborder="0" width="50%" height="100%" style="display: block; margin-left: auto;margin-right: auto;"></img>
								</span>
							</div>
							<div class="col-md-2">
								% <br>	 
								<input v-model="porc_sign" type="number" name="porc_sign" min="0" max="200" size="3" step="10">
							</div>
							<div class="col-md-5">
								<input type="file" id="upload_sign" ref="upload_sign" @change="change_sign" accept=".png" name="upload_sign">
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4">
								Archivo PDF:
							</div>
							<div class="col-md-6">
								<input type="file" id="upload_file" ref="upload_pdf" @change="change_pdf" accept="application/pdf" name="pdf">
							</div>
						</div>
					</div>
					<div v-if="loaded" class="card-body">
						<div class="row">
							<div class="col-md-4">
								<button v-on:click="getPreview" class="btn btn-primary">
									Vista Preliminar
								</button>
							</div>
							<div v-if="stat_preview" class="col-md-4">
								<button v-on:click="download" class="btn btn-primary">
									Descargar archivo
								</button>
							</div>
						</div>
					</div>
					<div v-if="stat_preview" class="card-body">
						<div class="row">
							<div class="preview col-md-12">
								<iframe :src="filepreview" style="width: 100%; height:50vw; position: relative; allowfullscreen;"></iframe>					
								<!-- <iframe :src="filepreview" frameborder="0" width="100%" height="300" style="display:block; margin:0; padding:0; max-width: 100%; width: 100%; height:auto;"></iframe>					 -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	export default {
		props: ['user_id'],
		data() {
			return {
				'loaded' : false,
				'files_loaded': {
					'sign' : false,
					'back' : false,
				},
				'stat_preview' : false,
				'preview' : false,
				'filepreview' : false,
				'namedownload': "",
				'filename' : "images/guide/guide.jpg",
				'filebase' : "images/guide/guide.jpg",
				'filefirma' : "images/guide/sign_guide.png",
				'fileback' : '',
				'seccion' : 8 ,
				'horizontal' : 50 ,
				'vertical' : 50 ,
				'porc_sign' : 100,
				'hojas' : 'ultima',
				'options' : [
					{ text: 'Ùltima', value: 'ultima' }, 
					{ text: 'Todas', value: 'todas' }, 
					{ text: 'Rango', value: 'rango' }, 
				] ,
				'range_page' : "",
			}
		},

		mounted() {
			console.log('SignComponent.vue mounted.');
			this.getGuide(0);
		},


		watch: {
			porc_sign: function () {
				this.getGuide();
			},

			hojas: function () {
				this.getGuide();
			},

			range_page: function () {
				this.getGuide();
			},

			seccion: function (newValue, oldValue) {
				this.getGuide();
			},

			horizontal: function (newValue, oldValue) {
				this.getGuide();
			},

			vertical: function (newValue, oldValue) {
				this.getGuide();
			},

			files_loaded: {
				handler: function () {
						if ( this.files_loaded.sign && this.files_loaded.back ){
							this.loaded = true;
						} else {
							this.loaded = false;
						}
					},
					deep: true,
			},

		},

		methods: {
			download(){
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
		    var url = protocol+'//'+URLdomain+this.filepreview;
				axios({
						url: url,
            // url: 'http://localhost:8000/my.pdf',
            method: 'GET',
            responseType: 'blob',
        }).then((response) => {
             var fileURL = window.URL.createObjectURL(new Blob([response.data]));
             var fileLink = document.createElement('a');

             fileLink.href = fileURL;
             fileLink.setAttribute('download', this.namedownload);
             document.body.appendChild(fileLink);

             fileLink.click();
        });
			},
			getPreview()
			{
				this.stat_preview = false;
				var request = {
					user_id: this.user_id,
					filefirma : {
						path: 'images/back/',
						filepath: this.filefirma,	
					},
					fileback : this.fileback,
					seccion : this.seccion ,
					horizontal : this.horizontal ,
					vertical : this.vertical ,
					hojas : this.hojas,
					range_page : this.range_page,
					porc_sign : this.porc_sign,
				};

        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol+'//'+URLdomain+'/api/doc/preview';
        axios.post(url, request).then(response => {
					console.log('getPreview response.data: ',response.data);
					this.preview = response.data.success;
					this.filepreview = response.data.filepath+"?"+Date.now();
					this.namedownload = response.data.filename;
					if(!this.preview)
					{
						console.log('error preview, no se grabo');
					}else{
						this.stat_preview = true;
					}

				}).catch(function (error) {
					console.log('error preview', error);
				});

			},

			change_pdf()
			{
				this.files_loaded.back = false;
				this.stat_preview = false;
				var request = new FormData();
				request.append('file_back', event.target.files[0]);
				request.append('user_id', this.user_id);
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol+'//'+URLdomain+'/api/doc/saveBack';
        axios.post(url, request).then(response => {
					console.log('change_pdf response', response.data);

        	this.fileback = response.data;
        	this.files_loaded.back = true;

					// console.log('change_pdf', this.fileback);
					// console.log('change_pdf', this.files_loaded);

				}).catch(function (error) {
					console.log('error change_pdf', error);
				});
			},

			change_sign(event)
			{
				this.files_loaded.sign = false;
				this.stat_preview = false;
				var request = new FormData();
				request.append('file_sign', event.target.files[0]);
				request.append('user_id', this.user_id);
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol+'//'+URLdomain+'/api/guide/sign';
        axios.post(url, request).then(response => {
					console.log('change_sign response.data', response.data);

        	this.filefirma = response.data.path + response.data.filename;
        	this.files_loaded.sign = true;

        	this.getGuide('change_sign');

				}).catch(function (error) {
					console.log('error change_sign', error);
				});
			},

			getImgUrl(pic)
			{
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol + '//' + URLdomain + '/storage/' + pic;
				return url;
			},

			getGuide()
			{
				this.stat_preview = false;
				var xfilename = this.filebase.replace(/^.*[\\\/]/, '');
				var xfilefirma = this.filefirma.replace(/^.*[\\\/]/, '');

				var request = {
					user_id: this.user_id,
					filename: xfilename,
					filefirma: xfilefirma,
					fileout: 'blank.jpg',
					seccion: this.seccion,
					horizontal: this.horizontal,
					vertical: this.vertical,
					hojas: this.hojas
				};
        var URLdomain = window.location.host;
        var protocol = window.location.protocol;
        var url = protocol+'//'+URLdomain+'/api/guide/';
        axios.post(url, request).then(response => {
					console.log('getGuide', response.data);
					if(response.data.success)
					{
	        	this.filename = response.data.filebase['path'] + response.data.filebase['filename'] +"?" + Date.now();
	        	return true;
						console.log('getGuide filename: ', this.filename);						
					}
          console.log('error getGuide, success false: ', response.data);
          return false;
        }).catch(function (error) {
            console.log('error getGuide', error);
        });
			}
		}
	};
</script>

<style type="text/css">
    html, body, div#content { margin:0; padding:0; height:100%; }
    iframe { display:block; width:100%; border:none; }
</style>