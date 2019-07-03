import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AsNavComponent } from './as-nav.component';

describe('AsNavComponent', () => {
  let component: AsNavComponent;
  let fixture: ComponentFixture<AsNavComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AsNavComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AsNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
