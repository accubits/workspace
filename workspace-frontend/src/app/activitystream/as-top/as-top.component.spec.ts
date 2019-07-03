import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsTopComponent } from './as-top.component';

describe('AsTopComponent', () => {
  let component: AsTopComponent;
  let fixture: ComponentFixture<AsTopComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsTopComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsTopComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
