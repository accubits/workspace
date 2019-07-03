import { Injectable } from '@angular/core';
import { Ng4LoadingSpinnerService } from 'ng4-loading-spinner';
import { ToastService } from '../shared/services/toast.service';
import { SuperAdminApiService } from '../shared/services/super-admin-api.service';
import { SuperAdminDataService } from '../shared/services/super-admin-data.service';
import { Router } from '@angular/router';

@Injectable()
export class SuperAdminSandbox {
  constructor(
    private toastService: ToastService,
    public superAdminDataService: SuperAdminDataService,
    private spinner: Ng4LoadingSpinnerService,
    private superAdminApiService: SuperAdminApiService,
    private router: Router
  ) { }

  /* Sandbox to handle API call for getting drive file list[Start] */
  getCountryList() {
    this.spinner.show();
    // Accessing drive API service
    return this.superAdminApiService.getCountryList().subscribe((result: any) => {
      this.superAdminDataService.getCountryList.countries = result.data.countries;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting drive file list[end] */

   /* Sandbox to handle API call for getting drive file list[Start] */
   setCountry() {
    this.spinner.show();
    // Accessing drive API service
    return this.superAdminApiService.setCountry().subscribe((result: any) => {
       this.spinner.hide();
       this.superAdminDataService.deletePopUp.show = false;
       this.superAdminDataService.createCountry.show = false;
       this.getCountryList();
      
       this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.toastService.Error("Organization exist under this country. cannot delete!");
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting drive file list[end] */

   /* Sandbox to handle API call for getting drive file list[Start] */
   getVerticalList() {
    this.spinner.show();
    // Accessing drive API service
    return this.superAdminApiService.getVerticalList().subscribe((result: any) => {
      this.superAdminDataService.getVerticalList.verticals = result.data.verticals;
      this.spinner.hide();
    },
      err => {
        console.log(err);
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting drive file list[end] */

   /* Sandbox to handle API call for getting drive file list[Start] */
   setVertical() {
    this.spinner.show();
    // Accessing drive API service
    return this.superAdminApiService.setVertical().subscribe((result: any) => {
       this.spinner.hide();
       this.superAdminDataService.deletePopUp.show = false;
       this.superAdminDataService.createVertical.show = false;
       this.getVerticalList();
       this.toastService.Success(result.data.msg);
    },
      err => {
        console.log(err);
        this.toastService.Error("Organization exist under this country. cannot delete!");
        this.spinner.hide();
      })
  }
  /* Sandbox to handle API call for getting drive file list[end] */
  }