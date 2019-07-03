import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TrainingNavComponent } from './training-nav.component';

describe('TrainingNavComponent', () => {
  let component: TrainingNavComponent;
  let fixture: ComponentFixture<TrainingNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TrainingNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TrainingNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
