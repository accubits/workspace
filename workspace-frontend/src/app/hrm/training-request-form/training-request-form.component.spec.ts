import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TrainingRequestFormComponent } from './training-request-form.component';

describe('TrainingRequestFormComponent', () => {
  let component: TrainingRequestFormComponent;
  let fixture: ComponentFixture<TrainingRequestFormComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TrainingRequestFormComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TrainingRequestFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
