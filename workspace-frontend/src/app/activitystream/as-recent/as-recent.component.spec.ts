import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsRecentComponent } from './as-recent.component';

describe('AsRecentComponent', () => {
  let component: AsRecentComponent;
  let fixture: ComponentFixture<AsRecentComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsRecentComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsRecentComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
