import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UtilitiesRoutingModule } from './utilities-routing.module';
import { DatepickerComponent } from './datepicker/datepicker.component';

@NgModule({
  imports: [
    CommonModule,
    UtilitiesRoutingModule
  ],
  declarations: [DatepickerComponent]
})
export class UtilitiesModule { }
