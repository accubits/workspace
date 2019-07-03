import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AppraisalResultComponent } from './appraisal-result.component';

describe('AppraisalResultComponent', () => {
  let component: AppraisalResultComponent;
  let fixture: ComponentFixture<AppraisalResultComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AppraisalResultComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AppraisalResultComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
