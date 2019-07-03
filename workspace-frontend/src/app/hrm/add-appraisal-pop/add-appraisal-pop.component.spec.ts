import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddAppraisalPopComponent } from './add-appraisal-pop.component';

describe('AddAppraisalPopComponent', () => {
  let component: AddAppraisalPopComponent;
  let fixture: ComponentFixture<AddAppraisalPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddAppraisalPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddAppraisalPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
