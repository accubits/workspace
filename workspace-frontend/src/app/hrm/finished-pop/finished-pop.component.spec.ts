import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FinishedPopComponent } from './finished-pop.component';

describe('FinishedPopComponent', () => {
  let component: FinishedPopComponent;
  let fixture: ComponentFixture<FinishedPopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FinishedPopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FinishedPopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
