import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { CheckRoutingModule } from './check-routing.module';
import { CheckComponent } from './check.component';

@NgModule({
  imports: [
    CommonModule,
    CheckRoutingModule
  ],
  declarations: [CheckComponent]
})
export class CheckModule { }
