import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TrainingRequestTableComponent } from './training-request-table.component';

describe('TrainingRequestTableComponent', () => {
  let component: TrainingRequestTableComponent;
  let fixture: ComponentFixture<TrainingRequestTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TrainingRequestTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TrainingRequestTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
