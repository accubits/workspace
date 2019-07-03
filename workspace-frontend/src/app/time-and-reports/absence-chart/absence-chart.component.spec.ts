import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AbsenceChartComponent } from './absence-chart.component';

describe('AbsenceChartComponent', () => {
  let component: AbsenceChartComponent;
  let fixture: ComponentFixture<AbsenceChartComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AbsenceChartComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AbsenceChartComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
