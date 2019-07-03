import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AppraisalCycleComponent } from './appraisal-cycle.component';

describe('AppraisalCycleComponent', () => {
  let component: AppraisalCycleComponent;
  let fixture: ComponentFixture<AppraisalCycleComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AppraisalCycleComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AppraisalCycleComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
